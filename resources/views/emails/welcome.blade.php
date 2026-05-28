<x-mail::message>
# Welcome to AAYMCAVoting, {{ $user->name }}!

Your account has been successfully created. Please keep this email for your records.

**Your login details:**

| Field | Value |
|-------|-------|
| Name | {{ $user->name }} |
| Email | {{ $user->email }} |

You can use the email address above to log in at any time. Please keep it safe.

Before you can access the system, you will need to **verify your email address** by clicking the button below:

<x-mail::button :url="$verificationUrl">
Verify My Email Address
</x-mail::button>

If you did not create an account, no further action is required.

---
If you have any issues logging in, contact the administrator at [admin@africaymca.org](mailto:admin@africaymca.org).

Thanks,<br>
**{{ config('app.name') }}**<br>
Africa YMCA
</x-mail::message>
