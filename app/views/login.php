    <!-- Login Page -->
    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="auth-card">
                        <div class="auth-form">
                            <h2 class="auth-title">Login</h2>
                            <form>
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="E-mail" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label">Rember-me</label>
                                    </div>
                                    <a href="lost_password" class="text-rlp">Forgot your password?</a>
                                </div>
                                <button class="auth-btn">Sign in</button>

                                <div class="social-login">
                                    <p class="text-center mb-3">Or enter with</p>
                                    <button class="social-btn google">
                                        <i class="fab fa-google me-2"></i> Google
                                    </button>
                                    <button class="social-btn facebook">
                                        <i class="fab fa-facebook-f me-2"></i> Facebook
                                    </button>
                                </div>

                                <div class="container-link">
                                    <span>Don't have an account?&nbsp;-&nbsp;</span><a href="/index.php/register" class="text-rlp">Create one now</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cadastro Page (Similar structure with different form) -->
    <div class="auth-container" style="display: none;">
        <!-- Similar structure with registration fields -->
    </div>