<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<?php
require_once __DIR__ . '/menu.php';
require_once __DIR__ . '/viewer.php';
require_once __DIR__ . '/add.php';
require_once __DIR__ . '/edit.php';
require_once __DIR__ . '/delete.php';
?>
<?php
// Определяем активный раздел
$view = $_GET['view'] ?? 'viewer';
$validViews = ['viewer','add','edit','delete'];
if (!in_array($view, $validViews, true)) {
    $view = 'viewer';
}
?>

<main>
<h2>Лабораторная работа №9</h2>
<h1>Записная книжка</h1>
   <h1 style="margin-top:0;">Контакты</h1>

  <!-- Меню -->
  <?php echo render_menu(); ?>

  <!-- Контент -->
  <div style="margin-top:16px;">
    <?php
    switch ($view) {
        case 'viewer':
            $sort = $_GET['sort'] ?? 'created';
            $page = max(1, (int)($_GET['page'] ?? 1));
            echo render_viewer($sort, $page);
            break;
        case 'add':
            echo render_add();
            break;
        case 'edit':
            echo render_edit();
            break;
        case 'delete':
            echo render_delete();
            break;
    }
    ?>
  </div>
</main>

<?php include __DIR__ . '/footer.php'; ?>

