/*
 * This file is part of Moodle - https://moodle.org/
 *
 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <https://www.gnu.org/licenses/>.
 */

/*
 * What mod_flexaccess is responsible for, exercised in a browser: the in-course activity through
 * which a temporary visitor secures their own account.
 */

const { test, expect } = require('@playwright/test');
const { loginAs, fillPasswordUnmask, open, submitForm, chooseCourse } = require('./helpers');

const ADMIN_USER = process.env.FLEXACCESS_ADMIN_USER || 'admin';
const ADMIN_PASS = process.env.FLEXACCESS_ADMIN_PASS || 'Admin!23';
const COURSE_ID = process.env.FLEXACCESS_COURSE_ID;
const COURSE_NAME = process.env.FLEXACCESS_COURSE_NAME || 'My favourite course';

/**
 * Build a readable address that stays unique across retries.
 *
 * A retry would otherwise reuse an address that the first attempt already registered. The first
 * attempt - the one whose screenshots are used as illustrations - keeps the plain name.
 *
 * @param {string} local The local part, for example 'john.doe'.
 * @param {import('@playwright/test').TestInfo} testInfo The current test info.
 * @returns {string}
 */
function personEmail(local, testInfo) {
  return testInfo.retry ? `${local}.${testInfo.retry}@example.org` : `${local}@example.org`;
}

const CMID = process.env.FLEXACCESS_CMID;

test('the activity appears in the course', async ({ page }) => {
  test.skip(!COURSE_ID, 'FLEXACCESS_COURSE_ID not provided by the seed step');
  await loginAs(page, ADMIN_USER, ADMIN_PASS);
  await open(page, `/course/view.php?id=${COURSE_ID}`);
  await expect(page.locator('body')).toContainText('Secure my account for later');
});

test('a permanent user is told the account is already secured', async ({ page }) => {
  test.skip(!CMID, 'FLEXACCESS_CMID not provided by the seed step');
  await loginAs(page, ADMIN_USER, ADMIN_PASS);
  await open(page, `/mod/flexaccess/view.php?id=${CMID}`);
  // An account that is already permanent has nothing to activate.
  await expect(page.locator('body')).not.toContainText('Activate my account');
});

test('a temporary visitor is offered the activation form', async ({ page, context }) => {
  test.skip(!COURSE_ID || !CMID, 'Fixture not provided by the seed step');

  await context.clearCookies();
  await page.goto(`/auth/flexaccess/access.php?courseid=${COURSE_ID}`);
  const cont = page.getByRole('button', { name: /Continue/i });
  if (await cont.count()) {
    await cont.first().click();
  } else {
    await page.getByRole('link', { name: /Continue/i }).first().click();
  }
  await expect(page).toHaveURL(/course\/view\.php/);

  await page.goto(`/mod/flexaccess/view.php?id=${CMID}`);
  await page.waitForLoadState('domcontentloaded');
  // This is the point of the activity: the visitor can secure the account from inside the course.
  await expect(page.locator('input[name="email"]')).toBeVisible();
});

test('a temporary visitor secures the account through the activity', async ({ page, context }, testInfo) => {
  test.skip(!COURSE_ID || !CMID, 'Fixture not provided by the seed step');
  const email = personEmail('john.doe', testInfo);
  const password = 'P@$$w0rd!';

  await context.clearCookies();
  await page.goto(`/auth/flexaccess/access.php?courseid=${COURSE_ID}`);
  const cont = page.getByRole('button', { name: /Continue/i });
  if (await cont.count()) {
    await cont.first().click();
  } else {
    await page.getByRole('link', { name: /Continue/i }).first().click();
  }

  await page.goto(`/mod/flexaccess/view.php?id=${CMID}`);
  await page.waitForLoadState('domcontentloaded');
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="firstname"]', 'John');
  await page.fill('input[name="lastname"]', 'Doe');
  await fillPasswordUnmask(page, 'password', password);
  // The activity has its own wording; the auth form's button is called differently.
  await page.getByRole('button', { name: /Activate my account/i }).click();
  await expect(page.locator('body')).toContainText(/permanent/i);
});
