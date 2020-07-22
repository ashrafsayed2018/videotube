<?php

$username = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn']  : "";
?>

<div class="bottomVnaviagation">
    <div class="navigationItems">
        <div class="navigationItem">
            <a href="index.php">
                <img src="assets/images/icons/home.png">
                <span> الرئيسيه</span>
            </a>
        </div>
        <div class="navigationItem">
            <a href="trending.php">
                <img src="assets/images/icons/trending.png">
                <span> المحتوى الرائج</span>
            </a>
        </div>
        <div class="navigationItem">
            <a href="subscriptions.php">
                <img src="assets/images/icons/subscriptions.png">
                <span> الاشتراكات</span>
            </a>
        </div>
        <div class="navigationItem">
            <a href="likedVideos.php">
                <img src="assets/images/icons/thumb-up.png">
                <span> فيديوهات اعجبتك</span>
            </a>
        </div>
        <div class="navigationItem">
            <a href="settings.php">
                <img src="assets/images/icons/settings.png">
                <span> الاعدادات</span>
            </a>
        </div>
        <div class="navigationItem">
            <a href="profile.php?username=<?= $username; ?>">
                <img src="assets/images/profilePictures/default.png">
                <span> قناتي</span>
            </a>
        </div>
    </div>
</div>
</div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/userActions.js"></script>
</body>
</html>