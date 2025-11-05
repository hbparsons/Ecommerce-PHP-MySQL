<!--this is the include for the log in modal-->

<div class="modal" id="log-in-modal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered mx-md-auto m-sm-4">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CandyCraze - Log In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="login.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username-log-in" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username-log-in" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password-log-in" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password-log-in" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="create-account.php" class="btn btn-primary" role="button">Create Account</a>
                    <button type="submit" class="btn btn-secondary">Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>