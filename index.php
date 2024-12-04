
<?php
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/cauhinh.php';
?>
<?php include_once 'core/head.php'; ?>

<div class="ant-col ant-col-xs-24 ant-col-sm-24 ant-col-md-24">
    <div class="page-layout-body">
        <!-- load view -->
        <div class="ant-row">
            <div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_"style ="color: #C0C0C0;">Bài viết mới</div>
        </div>
        <div class="ant-col ant-col-24">
            <div class="ant-list ant-list-split">
                <div class="ant-spin-nested-loading">
                    <div class="ant-spin-container">
                        <ul class="ant-list-items">
                            <div id="data_news">
                                <li class="ant-list-item home_page_listItem__GD_iE">
                                    <img src="/image/images/004.webp" class="home_page_listItemAvatar__cXjbm" />
                                    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
                                        <div class="ant-list-item-meta-content">
                                            <h4 class="ant-list-item-meta-title">
                                                <a href="/news/thongbaosk.php">🔥Thông Báo Sự Kiện ĐuaTop🔥</a>
                                            </h4>
                                            <div class="ant-list-item-meta-description">
                                                Đăng bởi: <b style="color: red;">Admin</b> - Ngày: 1/1/2025
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="ant-list-item home_page_listItem__GD_iE">
                                    <img src="/image/images/002.webp" class="home_page_listItemAvatar__cXjbm" />
                                    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
                                        <div class="ant-list-item-meta-content">
                                            <h4 class="ant-list-item-meta-title">
                                                <a style = "color: #D1D3D4;" href="/news/giftcode.php">Giftcode🎁🎁🎁</a>
                                            </h4>
                                            <div class="ant-list-item-meta-description">
                                                Đăng bởi: <b class="text-warning">Admin</b> - Ngày: 1/1/2025
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="ant-list-item home_page_listItem__GD_iE">
                                    <img src="/image/images/005.webp" class="home_page_listItemAvatar__cXjbm" />
                                    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
                                        <div class="ant-list-item-meta-content">
                                            <h4 class="ant-list-item-meta-title">
                                                <a style = "color: #D1D3D4;" href="/news/cochegame.php">⚡️Cơ Chế Game⚡️</a>
                                            </h4>
                                            <div class="ant-list-item-meta-description">
                                                Đăng bởi: <b class="text-warning">Admin</b> - Ngày: 1/1/2025
                                            </div>
                                        </div>
                                    </div>
                                </li>
                          <!--      <li class="ant-list-item home_page_listItem__GD_iE">
                                    <img src="https://media-ten.z-cdn.me/82PbjZ4v19wAAAAl/tkthao219-bubududu.webp" class="home_page_listItemAvatar__cXjbm" />
                                    <div class="ant-list-item-meta home_page_listItemTitle__YB3V5">
                                        <div class="ant-list-item-meta-content">
                                            <h4 class="ant-list-item-meta-title">
                                                <a href="/Forum.php">ĐĂNG BÀI</a>
                                            </h4>
                                            <div class="ant-list-item-meta-description">
                                                Đăng bởi: <b style="color: red;">Admin</b> - Ngày: 10/10/2222
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div> -->
                            <div id="paging" class="d-flex justify-content-end align-items-center flex-wrap">
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-banner-slide">
            <div class="slider">
                <div class="row position-relative">
                    <div class="col-12 slider_in">
                        <div class="swiper-container mySwiper slider_detail swiper-container-horizontal">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <img src="/image/traidatne.png" alt=""
                                            class="img-fluid swiper-lazy w-100 loading_lazy" data-ignore="true">
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <img src="/image/namecne.png" alt=""
                                            class="img-fluid swiper-lazy w-100 loading_lazy" data-ignore="true">
                                    </a>
                                </div>
                                <div class="swiper-slide">
                                    <a href="javascript:void(0)">
                                        <img src="/image/saiyanne.png" alt=""
                                            class="img-fluid swiper-lazy w-100 loading_lazy" data-ignore="true">
                                    </a>
                                </div>
                            </div>
                            <img class="swiper-button-prev" src="/image/mui_ten_2.png">
                            <img class="swiper-button-next" src="/image/mui_ten_1.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Swiper JS -->
        <!-- Swiper JS -->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var mySwiper = new Swiper('.mySwiper', {
                    loop: true,
                    centeredSlides: true, // Centered slide mode
                    slidesPerView: 1, // Number of slides per view
                    spaceBetween: 10, // Space between slides
                    autoplay: {
                        delay: 2000, // Auto play interval in milliseconds
                        disableOnInteraction: false, // Enable/disable interaction to stop autoplay
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 1, // Number of slides per view for larger screens
                        },
                    },
                });
            });
        </script>
        <div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Hướng Dẫn Tân Thủ </div>
        <div class="ant-col ant-col-24">
            <div class="home_page_listItem__GD_iE" style="display:flex">
                1. Đăng ký tài khoản
                <br />
                Bạn có thể đăng ký tài khoản miễn phí ngay trên
                trang Diễn Đàn.
                <br />
                Admin không bao giờ hỏi mật khẩu của bạn.
                <br />
                2. Hướng dẫn
                điều khiển
                <br />
                Đối với máy bàn phím: Dùng phím mũi tên, phím số, để điều
                khiển nhân vật. Phím chọn giữa để tương tác.
                <br />
                Đối với máy cảm ứng: Dùng
                tay chạm vào màn hình cảm ứng để di chuyển. Chạm nhanh 2 lần vào 1 đối tượng
                để tương tác.
                <br />
                Đối với PC: Dùng chuột, click chuột phải để di chuyển,
                click chuột trái để chọn, click đôi vào đối tượng để tương tác
                <br />
                3. Một số thông tin căn bản
                <br />
                - Đậu thần dùng để tăng KI và HP ngay lập
                tức.
                <br />
                - Bạn chỉ mang theo người được 10 hạt đậu. Nếu muốn mang nhiều
                hơn, hãy xin từ bạn bè trong Bang.
                <br />
                - Tất cả các sách kỹ năng đều có thể học miễn phí tại Quy Lão Kame, khi bạn có đủ điểm tiềm năng.
                <br />
                - Bạn không thể bay, dùng kỹ năng, nếu hết KI.
                <br />- Tấn công quái vật cùng bạn bè trong Bang sẽ mang lại nhiều điểm tiềm năng hơn đánh một mình.
                <br />- Tập luyện với bạn bè tại khu vực thích hợp sẽ mang lại nhiều điểm tiềm năng hơn
                đánh quái vật.
                <br />- Khi được nâng cấp, đậu thần sẽ phục hồi nhiều HP và KI hơn.
                <br />- Vào trò chơi đều đặn mỗi ngày để nhận được Ngọc miễn phí.
                <br />- Đùi gà sẽ phục hồi 100% HP, KI. Cà chua phục hồi 100% KI. Cà rốt phục hồi
                100% HP.
                <br />- Cây đậu thần kết một hạt sau một thời gian, cho dù bạn đang
                offline.
                <br />- Sau 3 ngày không tham gia trò chơi, bạn sẽ bị giảm sức mạnh
                do lười luyện tập.
                <br />- Bạn sẽ giảm thể lực khi đánh quái, nhưng sẽ tăng
                lại thể lực khi không đánh nữa.
                <br />
            </div>
        </div>
        <div class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Giới thiệu</div>
        <div class="ant-col ant-col-24 home_page_textDetail__BTiTp">
            <div style="background:#e37745;padding:10px;border-radius:10px">Trò Chơi Trực Tuyến với cốt truyện xoay
                quanh bộ truyện tranh 7 viên Ngọc
                Rồng. Người chơi sẽ hóa thân thành một trong những anh hùng của 3 hành tinh:
                Trái Đất, Xayda, Namếc. Cùng luyện tập, tăng cường sức mạnh và kỹ năng. Đoàn
                kết cùng chiến đấu chống lại các thế lực hung ác. Cùng nhau tranh tài. Đặc
                điểm nổi bật:<br />- Thể loại hành động, nhập vai. Trực tiếp điều khiển nhân
                vật hành động. Dễ chơi, dễ điều khiển nhân vật. Đồ họa sắc nét. Có phiên bản
                đồ họa cao cho điện thoại mạnh và phiên bản pixel cho máy cấu hình
                thấp.<br />- Cốt truyện bám sát nguyên tác. Người chơi sẽ gặp tất cả nhân
                vật từ Bunma, Quy lão kame, Jacky-chun, Tàu Pảy Pảy... cho đến Fide, Pic,
                Poc, Xên, Broly, đội Bojack.<br />- Đặc điểm nổi bật nhất: Tham gia đánh
                doanh trại độc nhãn. Tham gia đại hội võ thuật. Tham gia săn lùng ngọc rồng
                để mang lại điều ước cho bản thân.<br />- Tương thích tất cả các dòng máy
                trên thị trường hiện nay: Máy tính PC, Điện thoại di động Nokia Java,
                Android, iPhone, Windows Phone, và máy tính bảng Android, iPad.
            </div>
        </div>
    </div>
    <!-- end load view -->
</div>
</div>
<?php include_once 'core/footer.php'; ?>
</div>
</div>
</div>
</div>
</main>
</section>
</body>

</html>