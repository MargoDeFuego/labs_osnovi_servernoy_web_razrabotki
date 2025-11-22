<?php
require_once __DIR__ . '/config.php';
?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '../../nav.php'; ?>

<main>
<h2>Лабораторная работа №7</h2>
<h1>Ввод данных и сортировка массивов</h1>
<h2>Задайте массив:</h2>
  <form id="arrayForm" method="post" target="_blank" action="sort.php">
    <div id="elements">
      <div class="element">
        <label>Элемент 1:</label>
        <input type="text" name="arr[]">
      </div>
    </div>

    <label>Алгоритм сортировки:</label>
    <select name="algorithm">
      <option value="selection">Сортировка выбором</option>
      <option value="bubble">Пузырьковая сортировка</option>
      <option value="shell">Алгоритм Шелла</option>
      <option value="gnome">Алгоритм садового гнома</option>
      <option value="quick">Быстрая сортировка</option>
      <option value="php_sort">Встроенная функция PHP sort()</option>
    </select>
    <br><br>

    <button type="button" onclick="addElement()">Добавить еще один элемент</button>
    <button type="submit">Сортировать массив</button>
  </form>

  <script>
    let count = 1;
    function addElement() {
      count++;
      const div = document.createElement('div');
      div.className = 'element';
      div.innerHTML = `<label>Элемент ${count}:</label>
                       <input type="text" name="arr[]">`;
      document.getElementById('elements').appendChild(div);
    }
</script>
</main>


<?php include __DIR__ . '/footer.php'; ?>

