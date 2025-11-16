USER FOLDER - All Roles

Structure:
- user/admin/         : Admin pages (dashboard, manage users/projects, reports)
- user/client/        : Client pages (dashboard, create project, my projects, view bids)
- user/freelancer/    : Freelancer pages (dashboard, browse, bids, earnings, profile)
- user/moderator/     : Moderator pages (dashboard, flagged projects, disputes, reports)
- role_redirect.php   : Redirect user to their dashboard based on role

Integration:
1. Place this 'user' folder inside the SkillSwap project root (same level as controllers and includes)
2. Ensure config/Database.php contains correct DB credentials.
3. Pages assume controllers (ProjectController, BidController, ClientController, AuthController) exist.
4. Access via /skillswap/user/<role>/<page>.php or /skillswap/user/role_redirect.php after login.
