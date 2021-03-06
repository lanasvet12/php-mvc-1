<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

<?php
$counter = 0;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$counter = ($page - 1) * 10;

$slug = '';
foreach ($data['posts'] as $post) {
    if ($counter <= $page * 10) {
        $slug = $post['slug'];
        $imgSrc = '';
        if (preg_match_all("<img src=\"(.*?)\">", $post['body'], $match)) $imgSrc = str_replace('img src=', '', $match[0][0]);
        ?>
        <div class="row">
            <div class="col-12 bg-light px-0">
                <small class="text-secondary border-left border-right border-secondary px-2 position-absolute rotate90 topRightOuter">📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?></small>
                <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body"><h1
                            class="display-3 text-center mx-5"><?= $post['title']; ?></h1></a>
                <div class="media">
                    <?php
                    if ($imgSrc !== '') {
                        ?>
                        <img src=<?= $imgSrc ?> class="mr-2 leftMediaBlog" alt="<?= $post['title']; ?>">
                        <?php
                    }
                    ?>
                    <div class="media-body">
                        <hr class="mb-1 mt-0 ml-2 mr-2 mr-sm-5">
                        <p class="my-3 ml-2 mr-2 mr-sm-5"><?= $post['subtitle']; ?>...</p>
                        <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>"
                           class="text-dark border border-dark rounded-pill pl-2 pr-0 m-2 linkButton">Read More 〉</a>
                        <h6 class="float-sm-right mt-2 mt-sm-0 mx-2">
                            <a href="mailto:<?= userInfo($post['user_id'])['email']; ?>" class="text-dark"
                               data-toggle="tooltip" data-placement="left"
                               title="<?= userInfo($post['user_id'])['tagline']; ?>">😊 <?= substr(userInfo($post['user_id'])['email'], 0, strpos(userInfo($post['user_id'])['email'], '@')); ?></a>
                            <?php
                            if (currentUser()['id'] === $post['user_id']) {
                                ?>
                                <a href="<?= URL_ROOT . '/blog/update/' . $post['slug'] ?>"
                                   class="badge badge-light">✍️</a>
                                <?php
                            }
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    $counter++;
}
?>
    <nav aria-label="Page navigation" class="custom-pagination mt-3">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page - 2 <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="<?= URL_ROOT . '/blog?page=' . ($page - 1) ?>"
                   tabindex="-1" <?php if ($page - 2 <= 1) echo 'aria-disabled="true"'; ?>>Previous</a>
            </li>
            <?php
            for ($i = max(1, $page - 2); $i <= floor(count($data['posts']) / 10) + 1; $i++) {
                if ($i === $page) echo '<li class="page-item active" aria-current="page"><a class="page-link">' . $page . '</a></li>';
                else echo '<li class="page-item"><a class="page-link" href="' . URL_ROOT . '/blog?page=' . $i . '">' . $i . '</a></li>';
            }
            ?>
            <li class="page-item <?php if (($page + 2) * 10 >= count($data['posts'])) echo 'disabled'; ?>">
                <a class="page-link"
                   href="<?= URL_ROOT . '/blog?page=' . ($page + 1) ?>" <?php if (($page + 2) * 10 >= count($data['posts'])) echo 'aria-disabled="true"'; ?>>Next</a>
            </li>
        </ul>
    </nav>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>