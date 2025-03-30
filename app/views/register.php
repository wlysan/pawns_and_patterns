<!-- Cadastro Page -->
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="auth-card">
                    <div class="auth-form">
                        <h2 class="auth-title">Create account</h2>
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control mb-3" placeholder="Name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control mb-3" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="E-mail" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" placeholder="Telephone" required pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" required>
                                <label class="form-check-label">
                                    <span>I accept the <a href="#" class="auth-text">Terms and Services.</a></span>
                                </label>
                            </div>
                            <button class="auth-btn">Create Account</button>

                            <div class="social-login">
                                <p class="text-center mb-3">Or register with</p>
                                <button class="social-btn google">
                                    <i class="fab fa-google me-2"></i> Google
                                </button>
                                <button class="social-btn facebook">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </button>
                            </div>

                            <div class="container-link">
                                <span>Already have an account?&nbsp;-&nbsp;</span><a href="/index.php/login" class="text-rlp">Log in</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>