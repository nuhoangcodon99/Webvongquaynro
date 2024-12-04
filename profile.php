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
                            
                            <div class="container color-forum pt-1 pb-1">
    <div class="row">
        <div class="col">
            <a href="/" style ="color: #E9ECEF;" class="ant-col ant-col-24 home_page_bodyTitleList__UdhN_">Quay lại diễn đàn</a>
        </div>
    </div>
</div>
<div id="profile-update"></div>
                            
    </div>
<div class="ant-col ant-col-24">
    <div class="ant-list ant-list-split">
        <div class="ant-spin-nested-loading">
            <div class="ant-spin-container">
                <ul class="ant-list-items">
<div id="data_news" >
                                            
<script>
    function updateRemainingTime() {
        fetch('../api/cauhinh/api-profile.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById("profile-update").innerHTML = data;
            })
            .catch(error => console.error(error));
    }

    setInterval(updateRemainingTime, 1000); // Cập nhật mỗi giây (1000ms)
</script>
                                                    </div>
                        <div id="paging" class="d-flex justify-content-end align-items-center flex-wrap">
                        </div>
                   </ul>
            </div>
        </div>
    </div>
</div>
</div>                            <!-- end load view -->
                        </div>
                    </div>
<?php include_once 'core/footer.php'; ?>
</body>
</html>