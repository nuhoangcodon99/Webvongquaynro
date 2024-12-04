<?php
require_once 'core/connect.php';
require_once 'core/set.php';
require_once 'core/cauhinh.php';
require_once 'core/vong-quay.php';
if ($_login === null) {
  echo '<script>window.location.href = "../dang-nhap.php";</script>';
}
?>

<body>
  <style>
    .titleSpin {
      position: absolute;
      background-color: red;
      font-family: Pony;
      color: white;
      font-size: 16px;
      padding: 10px;
      border-radius: 10%;
    }

    .showImg {
      opacity: 1;
      z-index: 1;
      position: relative;
      transform: translateY(-165px);
      background: white;
      border-radius: 15px;
      box-shadow: rgb(175 162 138 / 38%) 1px 1px 10px 6px;
    }
  </style>
  <?php include("core/head.php"); ?>
  <link rel="stylesheet" href="assets/css/ancapconcac.css">
  <!-- <link rel="stylesheet" href="assets/css/light.css"> -->
  <link rel="stylesheet" href="assets/css/main.css">
  <!-- <link rel="stylesheet" href="assets/css/style copy.css"> -->
  <link rel="stylesheet" href="assets/css/toastr.min.css">
  <!-- Thư viện toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Thư viện toastr JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <section class="section section game_rotation"><!---->
    <div class="section__content_rotation">
      <div class="inner_rotation inner--game_rotation">
        <div class="airblowing">
          <div id="anireward" class="anireward">
            <div class="anireward__item anireward__item--1"><img src="../assets/images/spin/MxrStMQ.png"></div>
            <div class="anireward__item anireward__item--2"><img src="../assets/images/spin/cFbZyHg.png"></div>
            <div class="anireward__item anireward__item--3"><img src="../assets/images/spin/J6Q30Tz.png"></div>
            <div class="anireward__item anireward__item--4"><img src="../assets/images/spin/byPkOwR.png"></div>
            <div class="anireward__item anireward__item--5"><img src="../assets/images/spin/02YbcFI.png"></div>
            <div class="anireward__item anireward__item--6"><img src="../assets/images/spin/SaQYFBU.png"></div>
            <div class="anireward__item anireward__item--7"><img src="../assets/images/spin/ZwxlZQw.png"></div>
            <div class="anireward__item anireward__item--8"><img src="../assets/images/spin/tdEvIFz.png"></div>
            <div class="anireward__item anireward__item--9"><img src="../assets/images/spin/Wch0Xo8.png"></div>
            <div class="anireward__item anireward__item--10"><img src="../assets/images/spin/l3fr7CW.png"></div>
            <div class="anireward__item anireward__item--11"><img src="../assets/images/spin/NtTiHP8.png"></div>
            <div class="anireward__item anireward__item--12"><img src="../assets/images/spin/55EQLjI.png"></div>
            <div class="anireward__item anireward__item--13"><img src="../assets/images/spin/tXexoPJ.png"></div>
            <div class="anireward__item anireward__item--14"><img src="../assets/images/spin/QbufFVD.png"></div>
            <div class="anireward__item anireward__item--15"><img src="../assets/images/spin/cGEiQkq.png"></div>
          </div>
          <div id="action" class="action"><a href="javascript:;" class="action__open action__open--1 pm__rut rut-1"><img
                src="../assets/images/spin/btn.png" class="action__btn"></a> <a href="javascript:;"
              class="action__open action__open--10 pm__rut rut-10"><img src="../assets/images/spin/btn.png"
                class="action__btn"></a></div>
        </div>
      </div>
    </div>
    <div class="spinModal">
    </div>
    </div>
    <div class="ant-col ant-col-24" style="background-color: #212529;">
      <div style="line-height:15px;font-size:12px;padding-bottom:10px;padding-top:6px;text-align:center">
        <img height="12" src="/public/images/12.png" style="vertical-align:middle" />
        <span style="vertical-align:middle; color: #D1D3D4;">Dành cho người chơi trên 12 tuổi. Chơi quá 180 phút mỗi
          ngày sẽ hại sức
          khỏe.</span><br /><br />

        <div>
          <h5 style="color: #D1D3D4;">
            2024© Được vận hành bởi <a href="">Ngọc Rồng Online</a>
          </h5>
        </div>
      </div>
  </section>
  <script>
    var rut1 = document.querySelector(".rut-1");
    var rut10 = document.querySelector(".rut-10");

    const addAndRemoveClass = (element, className, timeout) => {
      element.classList.add(className);
      setTimeout(() => {
        element.classList.remove(className);
      }, timeout);
    };

    rut10.addEventListener("click", () => {
      toastr.error("Chức năng đang phát triển!");
    });

    rut1.addEventListener("click", () => {
      handleButtonClick();
    });

    function handleButtonClick() {
      var anireward__item = document.querySelectorAll(".anireward__item");
      anireward__item.forEach((item, index) => {
        addAndRemoveClass(item, `anireward__running--${index + 1}`, 5000);
      });

      setTimeout(() => {
        $.ajax({
          method: "POST",
          url: "core/vong-quay.php",
          data: { type: 'x1' }, // Dữ liệu gửi lên server
          success: (response) => {
            try {
              var data = JSON.parse(response);

              if (data.error) {
                toastr["error"](data.message, "Error");
              } else {
                // Kiểm tra dữ liệu trả về trước khi xử lý
                if (data.data && data.data.length > 0 && data.giftCodes && data.giftCodes.length > 0) {
                  var spinModal = document.querySelector(".spinModal");
                  spinModal.innerHTML = `
            <div class="gift-container">
              <button type="button" class="btn btn-primary btn-close-gift">X</button>
              <div class="row justify-content-end">
                <div class="col-12 mt-5 d-flex justify-content-center">
                  <div class="titleSpin">${data.data[0].name}</div>
                  <!-- Kiểm tra nếu không có ảnh, hiển thị ảnh mặc định hoặc không có ảnh -->
                  <img style="height: auto; width: 50%; top: 100%" src="${data.data[0].img || 'default-img.png'}" class="img showImg">
                  <div class="message-success message-show">
                    <b>${data.giftCodes[0]}</b>
                  </div>
                </div>
              </div>
            </div>
            <div class="gift-background"></div>
          `;
                  document.querySelector(".btn-close-gift").addEventListener("click", () => {
                    document.querySelector(".spinModal").innerHTML = ``;
                  });
                } else {
                  toastr["error"]("Dữ liệu không hợp lệ!", "Error");
                }
              }
            } catch (e) {
              console.error("Dữ liệu trả về không phải là JSON hợp lệ:", response);
              toastr["error"]("Đã xảy ra lỗi khi xử lý dữ liệu!", "Error");
            }
          },
          error: () => {
            toastr["error"]("Đã xảy ra lỗi tại máy chủ!", "Error");
          }
        });

      }, 5000);
    }
  </script>