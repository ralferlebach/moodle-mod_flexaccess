# Activity flow

1. `require_login()` and `mod/flexaccess:view`.
2. Determine account type through `auth_flexaccess`.
3. `authenticated user`: show configured success/info text; no account mutation.
4. `temporary user`: show Moodle Form API form for name, E-Mail and allowlisted profile fields.
5. On POST: validate sesskey, rate limit, duplicate-email policy and configured fields.
6. Delegate provisional update + queued activation mail to `auth_flexaccess`.
7. E-Mail token verification happens in the auth plugin, not in the module.
8. On success, same `userid` becomes `authenticated user`; enrolment time is unchanged.
