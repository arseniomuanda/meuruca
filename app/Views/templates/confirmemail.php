<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Meu Ruca - Condirmação de Email</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <!-- <link href="/confirmaemail/assets/img/favicon.png" rel="icon"> -->
    <!-- <link href="/confirmaemail/assets/img/apple-touch-icon.png" rel="apple-touch-icon"> -->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/confirmaemail/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/confirmaemail/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/confirmaemail/assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Maundy
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/maundy-free-coming-soon-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container d-flex flex-column align-items-center">

            <div class="subscribe">
                <h4>Conrirmar Email!</h4>
                <form onsubmit="" method="post" role="form" class="confirm_email">
                    <div class="subscribe-form">
                        <input disabled readonly type="email" name="email"><input type="submit" value="Confirmar">
                    </div>
                    <div class="mt-2">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Email verificado com sucesso!</div>
                    </div>
                </form>
            </div>

            <div class="social-links text-center">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>

        </div>
    </header><!-- End #header -->

    <main id="main">

        <!-- ======= About Us Section ======= -->
        <section id="about" class="about">
            <div class="container">

                <div class="section-title">
                    <h2>Sobre o Meu Ruca</h2>
                    <p>Nós somos uma startup cujo o objectivo é solucionar problemas relacionados com a gestão e prestação de
                        serviço
                        do seu carro. a startup lançará uma <strong>APP</strong> E <strong>WEB</strong> grátis para todos que
                        precisam fazer a gestão do seu carro.</p>
                    <p>Funcionará como um hub de serviços de carros, vamos conectar os proprietários dos carros com os
                        melhores prestadores de serviços de angola, eles podem ir ao encontro do seu carro, no seu serviço, casa,
                        escritório ou em qualquer lugar onde estiveres.</p>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-4 col-md-6 icon-box">
                        <div class="icon"><i class="bi bi-briefcase"></i></div>
                        <h4 class="title"><a href="">Gestão da viatura</a></h4>
                        <p class="description">Registo de uma ou mais viaturas;</p>
                        <p class="description">Controlo das manutenções;</p>
                        <p class="description">Alertas por sms ou notificações sobre a manutenção;</p>
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box">
                        <div class="icon"><i class="bi bi-bar-chart"></i></div>
                        <h4 class="title"><a href="">Prestação de Serviço</a></h4>
                        <p class="description">Solicitação de serviço como: lavagem de carro manutenção de carro vendas de peças
                    </div>
                    <div class="col-lg-4 col-md-6 icon-box">
                        <div class="icon"><i class="bi bi-brightness-high"></i></div>
                        <h4 class="title"><a href="">Vendas e compras</a></h4>
                        <p class="description">Vendas para vendedores de carro</p>
                    </div>
                </div>

            </div>
        </section><!-- End About Us Section -->

        <!-- ======= Contact Us Section ======= -->
        <section id="contact" class="contact">
            <div class="container">

                <div class="section-title" data-aos="fade-up">
                    <h2>Contact</h2>
                    <p>Contact Us</p>
                </div>

                <div class="row">

                    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
                        <div class="info">
                            <div class="address">
                                <i class="bi bi-geo-alt"></i>
                                <h4>Localização:</h4>
                                <p>Centralidade do Kilamba, Bloco R, Edifício R2, Porta 12</p>
                            </div>

                            <div class="email">
                                <i class="bi bi-envelope"></i>
                                <h4>E-mail:</h4>
                                <p>info@meuruca.ao</p>
                            </div>

                            <div class="phone">
                                <i class="bi bi-phone"></i>
                                <h4>Telefone:</h4>
                                <p>+244 938 520 949</p>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-8 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">

                        <form method="post" role="form" class="php-email-form">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 form-group mt-3 mt-md-0">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
                            </div>
                            <div class="form-group mt-3">
                                <textarea class="form-control" name="message" id="message" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Send Message</button></div>
                        </form>

                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Maundy</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/maundy-free-coming-soon-bootstrap-theme/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End #footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/confirmaemail/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/confirmaemail/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="/confirmaemail/assets/js/main.js"></script>
    <script>
        let forms = document.querySelectorAll('.confirm_email');

        forms.forEach(function(e) {
            e.addEventListener('submit', function(event) {
                event.preventDefault();

                let thisForm = this;

                let action = thisForm.getAttribute('action');
                let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');


                thisForm.querySelector('.loading').classList.add('d-block');
                thisForm.querySelector('.error-message').classList.remove('d-block');
                thisForm.querySelector('.sent-message').classList.remove('d-block');

                let formData = new FormData(thisForm);
                formData.append('email', '<?= $email ?>');

                if (recaptcha) {
                    if (typeof grecaptcha !== "undefined") {
                        grecaptcha.ready(function() {
                            try {
                                grecaptcha.execute(recaptcha, {
                                        action: 'php_email_form_submit'
                                    })
                                    .then(token => {
                                        formData.set('recaptcha-response', token);
                                        php_email_form_submit(thisForm, action, formData);
                                    })
                            } catch (error) {
                                displayError(thisForm, error);
                            }
                        });
                    } else {
                        displayError(thisForm, 'The reCaptcha javascript API url is not loaded!')
                    }
                } else {
                    php_email_form_submit(thisForm, formData);
                }
            });
        });

        function php_email_form_submit(thisForm, formData) {
            fetch('/login/setConfirmedEmail/<?= $token ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error(`${response.status} ${response.statusText} ${response.url}`);
                    }
                })
                .then(data => {
                    thisForm.querySelector('.loading').classList.remove('d-block');
                    if (data.trim() == 'OK') {
                        thisForm.querySelector('.sent-message').classList.add('d-block');
                        thisForm.reset();
                        setTimeout(() => {
                            localtion = 'https://cliente.meuruca.ao';
                        }, 5000);
                    } else {
                        throw new Error('Token expirado!');
                    }
                })
                .catch((error) => {
                    displayError(thisForm, error);
                });
        }

        function displayError(thisForm, error) {
            thisForm.querySelector('.loading').classList.remove('d-block');
            thisForm.querySelector('.error-message').innerHTML = error;
            thisForm.querySelector('.error-message').classList.add('d-block');
        }
    </script>

</body>

</html>