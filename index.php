<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý ký túc xá</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" />
    <link rel="shortcut icon" href="images/favicon.ico">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/home.css" />

    <style>
        .modal-body {
            /*background-color: rgba(11,11,11,.5);*/    
            background-color: #303841;
        }

        .carousel-inner {
            width: 100%;

        }

        .bdr {
            border-bottom: 2px solid red;
        }

        .radius {
            border-radius: 15px;
            transition: 0.5s all ease;
        }

        .radius:hover:after {

            content: " >";

        }

        .slick-initialized .slick-slide {
            outline: none;
        }

        .checked {
            color: orange;
        }

        .img-hv {
            transition: 0.5s all ease;
        }

        .img-hv:hover {
            transform: scale(1.2, 1.2);
        }

        .active,
        .menu-bar ul li:hover {
            background: #2bab0d;
            border-radius: 5px;

        }

        .sub-menu-1 {
            display: none;
        }

        .menu-bar ul li:hover .sub-menu-1 {
            display: block;
            position: absolute;
            background: rgb(0, 100, 0);
            margin-top: 15px;
            margin-left: -15px;

        }

        .menu-bar ul li:hover .sub-menu-1 ul {
            display: block;
            margin: 10px;
        }
    </style>
</head>

<body>
    <header class="header">
        <img src="images/tlu.png" alt style="height: 60px; width: 65px">
        <a href="#" class="logo" style="text-decoration: none;">Hệ thống quản lý ký túc xá</a>
        <nav class="navbar">
            <a href="#about" style=" text-decoration:none; ">Thông tin</a>
            <a href="#provost" style=" text-decoration:none; ">Thông báo</a>
            <a href="#hall" style=" text-decoration:none; ">Khu</a>
            <a href="#Gallery" style=" text-decoration:none; ">Cơ sở vật chất</a>
            <button id="admin" style="margin-left: 20px;height: 40px;width: 120px;"><a href="login.php" style=" text-decoration:none; text-align:centre; color:aliceblue" id="link1">Đăng nhập</a></button>
        </nav>

    </header>

    <!-- main slide -->
    <div id="pslide" class="carousel slide" data-ride="carousel" style="margin-left: 40px; margin-right:40px">

        <!-- slider -->

        <div class="carousel-inner" data-interval="500">
            <!-- 1st slider -->
            <div class="carousel-item active">
                <!-- slider caption -->
                <div class="carousel-caption d-none d-md-block">
                    <!--   <h2 class="display-1 text-danger">This is my first slider</h2> -->
                </div>
                <img src="images/1.png" height="600px" width="100%" alt="">
            </div>
            <!-- 2nd slider -->
            <div class="carousel-item">
                <!-- slider caption -->
                <div class="carousel-caption">
                    <!--  <h2 class="display-1">This is my second slider</h2> -->
                </div>
                <img src="images/h1.jpg" height="600px" width="100%" alt="">
            </div>
            <!-- 3rd slider -->
            <div class="carousel-item">
                <!-- slider caption -->
                <div class="carousel-caption">
                    <!--   <h2 class="display-1">This is my third slider</h2> -->
                </div>
                <img src="images/h9.jpg" height="600px" width="100%" alt="">
            </div>
            <!-- 4th slider -->
            <div class="carousel-item">
                <!-- slider caption -->
                <div class="carousel-caption">
                    <!--    <h2 class="display-1">This is my third slider</h2> -->
                </div>
                <img src="images/2.jpg" height="600px" width="100%" alt="">
            </div>
        </div>

        <!-- next and prev icon -->
        <a href="#pslide" class="carousel-control-prev" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a href="#pslide" class="carousel-control-next" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>
    <!-- slider end -->

    <!-- about us section starts  -->
    <center>
        <h1 class="heading" style="margin-bottom: -50px;"> <span>About</span> </h1>
    </center>
    <section class="ftco-wrap-about">

        <div class="intro" id="about">
            <div class="container" id="sec_cond">
                <div class="row">
                    <div class="col">
                        <div class="intro_content" style="width: 550px; margin-right: 172px;height: 415px;">
                            <!-- <div class="intro_subtitle page_subtitle">About Us</div> -->
                            <div class="intro_title">
                                <h3>Chào mừng</h3>
                            </div>
                            <div class="intro_text">
                                <p>Xin chào và chào mừng bạn đến với ký túc xá Đại học Thủy Lợi! Chúng tôi rất vui mừng khi được chào đón bạn trở thành một phần của cộng đồng nơi đây. Ký túc xá không chỉ là nơi để nghỉ ngơi mà còn là không gian để bạn kết nối, giao lưu và tạo dựng những kỷ niệm đáng nhớ trong quãng đời sinh viên.

                                    Chúng tôi cung cấp đầy đủ tiện nghi để đảm bảo cuộc sống của bạn thoải mái và thuận tiện, từ phòng tự học, khu sinh hoạt chung cho đến hệ thống giặt là. Đội ngũ quản lý luôn sẵn sàng hỗ trợ bạn bất cứ khi nào bạn cần.

                                    Hãy tận hưởng cuộc sống tại ký túc xá, làm quen với những người bạn mới và chuẩn bị cho một hành trình đại học đầy ý nghĩa! </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6.5 col-md-6 intro_col">
                                <div class="intro_image"><img src="images/slider5.jpg" alt="intro" style="max-width: 100%;
    border-radius: 0px 0px 10px 10px; border: 2px solid darksalmon"></div>
                            </div>
                            <!-- <div class="col-xl-4 col-md-4 intro_col">
                                <div class="intro_image"><img src="images/img2.jpg" alt="intro"></div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about section end -->

    <!-- provost  start -->
    <section class="provost" id="provost">

        <center>
            <h1 class="heading" style="margin-top: 10px;"> <span>Thông báo</span> </h1>
        </center>

        <div class="box-container">

            <div class="box">
                <h3 align="left" style="padding: 5px 10px 5px 70px;">
                    Kính gửi các bạn sinh viên Ký túc xá,

                    <br><br>Nhân dịp Ngày Nhà giáo Việt Nam 20/11, chúng tôi trân trọng mời các bạn tham gia buổi gặp mặt và tri ân thầy cô. Sự kiện sẽ diễn ra vào:

                    <br><br>📅 Thời gian: 19h00, ngày 19/11
                    <br><br>📍 Địa điểm: Hội trường Ký túc xá

                    <br><br>Buổi lễ sẽ có các hoạt động giao lưu, chia sẻ và trao quà tri ân thầy cô.

                    <br><br>Để tham gia, các bạn vui lòng đăng ký tại Văn phòng Quản lý Ký túc xá trước ngày 17/11.

                   <br><br>Mọi thắc mắc, vui lòng liên hệ văn phòng KTX. Rất mong sự góp mặt của các bạn để cùng tạo nên một buổi tối ý nghĩa!

                   <br><br>Trân trọng,
                    <br><br>Ban Quản lý Ký túc xá
                </h3>
            </div>
        </div>
    </section>

    <!-- doctors section starts  -->

    <section class="hall" id="hall">

        <h1 class="heading"> Khu </h1>

        <div class="box-container">

            <div class="box">
                <img src="images/h1.jpg" alt="">
                <h3>Khu K11 </h3>
            </div>

            <div class="box">
                <img src="images/h2.jpg" alt="">
                <h3>Khu KTX Việt Nhật</h3>
            </div>

        </div>

    </section>

    <!-- doctors section ends -->


    <!-- Galary section start-->
    <div class="container-fluid pt-5 pb-3" id="Gallery" style="margin-top:20px">
        <h1 class=" heading">Cơ sở vật chất</h1>
        <div class="row">
            <div class="col-12 text-center mb-2">
                <ul class="list-inline mb-4" id="portfolio-flters">
                    <li class="btn btn-sm btn-outline-primary m-1 active" data-filter="*" style=" text-decoration:none; ">Tất cả</li>
                    <li class="btn btn-sm btn-outline-primary m-1" data-filter=".first"><a href="#room" style=" text-decoration:none; "> Phòng </a>
                    </li>
                    <li class="btn btn-sm btn-outline-primary m-1" data-filter=".second"><a href="#floor" style=" text-decoration:none; ">Hành lang</li>
                    <li class="btn btn-sm btn-outline-primary m-1" data-filter=".third"><a href="#canteen" style=" text-decoration:none; ">Thiết bị</li>
                </ul>
            </div>
        </div>
        <div class="container" id="cont">
            <div class="position-relative d-flex align-items-center justify-content-center" id="sec_cond1">
                <div class="row portfolio-container" id="room">
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item first">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/Room1.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/Room1.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item second" id="room">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/Room2.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/Room2.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item second" id="room">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/Room3.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/Room3.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item third" id="floor">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/Floor1.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/Floor1.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item first" id="floor">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/Floor2.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/Floor2.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item third" id="floor">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/Floor3.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/Floor3.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item second" id="canteen">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/canteen1.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/canteen1.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item third" id="canteen">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/canteen2.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/canteen2.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4 portfolio-item third" id="canteen">
                        <div class="position-relative overflow-hidden mb-2">
                            <img class="img-fluid rounded w-100" src="images/canteen3.jpg" alt="">
                            <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                                <a href="images/canteen3.jpg" data-lightbox="portfolio">
                                    <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Gallery End -->


    <!-- footer start -->

    <section class="footer" id="footer">

        <div class="box-container">

            <div class="box">
                <h3>quick links</h3>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> Thông tin </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> Thông báo </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> Khu </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> Cơ sở vật chất </a>
            </div>

            <div class="box">
                <h3>Useful Links</h3>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> JS </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> CSS </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> PHP </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> LARAVEL </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-chevron-right"></i> HTML </a>
            </div>

            <div class="box">
                <h3>contact info</h3>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-phone" style="text-decoration: none;"></i> 0123456789 </a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-phone" style="text-decoration: none;"></i> 0987654321</a>
                <a href="#" style="text-decoration: none;"> <i class="fas fa-envelope"></i> vivanquy20804@gmail.com </a>
                <a href="https://www.google.com/maps/place/Shaheed+Salam+Barkat+Hall/@23.8823987,90.2620247,17z/data=!3m1!4b1!4m5!3m4!1s0x3755e9a02b7f1e89:0x6d20e40f3a231f37!8m2!3d23.8823987!4d90.2642134" style="text-decoration: none;"> <i class="fas fa-map-marker-alt"></i> Trần Duy Hưng</a>
            </div>

            <div class="box">
                <h3>follow us</h3>
                <a target="_blank" href="https://www.facebook.com/ssbhju" target="_blank" style="text-decoration: none;"> <i class="fab fa-facebook-f"></i> facebook </a>
                <a href="#" style="text-decoration: none;"> <i class="fab fa-twitter"></i> twitter </a>
                <a href="#" style="text-decoration: none;"> <i class="fab fa-instagram"></i> instagram </a>
                <a href="#" style="text-decoration: none;"> <i class="fab fa-linkedin"></i> linkedin </a>
                <a href="#" style="text-decoration: none;"> <i class="fab fa-pinterest"></i> pinterest </a>
            </div>

        </div>



    </section>



    <!-- footer end -->
    <!-- footer -->
    <div class="container-fluid bg-dark text-white mt-5 py-1 px-sm-1 px-md-5" style="height: 150px;">
        <div class="container text-center py-5">
            <div class="d-flex justify-content-center mb-4" style="margin-top: 5px;">
                <a class="btn btn-light btn-social mr-2" href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-light btn-social mr-2" href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-light btn-social mr-2" href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a class="btn btn-light btn-social" href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="credit">
                <h2>created by <span>Group 7</span> | @all rights reserved</h2>
            </div>
        </div>
    </div>
    <!-- footer -->















    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!--  smooth scroll -->
    <script src="https://cdn.jsdelivr.net/gh/cferdinandi/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- jquery -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- slick slider js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous"></script>
    <script src="../js/main.js"></script>
    <script>
        $('.pslick').slick({
            dots: false,
            infinite: true,
            speed: 300,
            autoplay: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        arrows: false,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]
        });
    </script>

</body>

</html>