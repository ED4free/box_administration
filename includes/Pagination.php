<?php
function print_pagination_selector() {
?>
<div class="tablenav-pages">
<span class="displaying-num" id="nb-elem">
    0 éléments
</span>
<span class="pagination-links">
    <button onClick="firstPage()">
    «
    </button>
    <button onClick="prevPage()">
    ‹
    </button>
    <span class="paging-input">
    <label for="current-page-selector" class="screen-reader-text">
        Page actuelle
    </label>
    <input class="current-page" id="current-page-selector" type="number" name="paged" value="1" max="1" min="1" size="2" aria-describedby="table-paging" onChange="curPageChange()">
    <span class="tablenav-paging-text">
        sur 
        <span class="total-pages" id="total-pages">
        1
        </span>
    </span>
    </span>
    <button onClick="nextPage()">
    › 
    </button>
    <button onClick="lastPage()">
    »
    </button>
</span>
<br/>
éléments par page
<input type="number" value="10" id="per-page-selector" onChange="perPageChange()" />
</div>
<?php
}

function print_pagination_script() {
    ?>
<script type='text/javascript'>
  var blogs = [];
  var per_page = document.getElementById("per-page-selector");
  var cur_page = document.getElementById("current-page-selector");
  var nb_elem = document.getElementById("nb-elem");
  var total_pages = document.getElementById("total-pages");

  

  function actualizeNbElem() {
    nb_elem.innerHTML = blogs.length + " element";
    if (!blogs.empty)
      nb_elem.innerHTML += "s";
    total_pages.innerHTML = Math.trunc(blogs.length / per_page.value) + 1;
    cur_page.max = Math.trunc(blogs.length / per_page.value) + 1;
  }

  function curPageChange() {
    if (cur_page.value > Number(total_pages.innerHTML)) {
      cur_page.value = total_pages.innerHTML;
    }
    if (cur_page.value < 1) {
      cur_page.value = 1;
    }
    refreshTable();
  }

  function perPageChange() {
    actualizeNbElem();
    curPageChange()
  }

  function firstPage() {
    cur_page.value = 1;
    refreshTable();
  }

  function lastPage() {
    cur_page.value = total_pages.innerHTML;
    refreshTable();
  }

  function prevPage() {
    if (cur_page.value > 1) {
      cur_page.value--;
    }
    refreshTable();
  }

  function nextPage() {
    if (cur_page.value < Number(total_pages.innerHTML)) {
      cur_page.value++;
    }
    refreshTable();
  }
</script>
<?php
}

function refresh_twin_table_function() {
    ?>
<script type='text/javascript'>
  function refreshTable() {
    clearTable();
    for (var i = 0; (cur_page.value - 1) * per_page.value + i < blogs.length && i < per_page.value; ++i){
      curBlog = blogs[(cur_page.value - 1) * per_page.value + i];
      addTwinningsRow(
        curBlog["twinUid"],
        curBlog["blogName"],
        curBlog["date"],
        curBlog["schoolName"],
        curBlog["size"]
      );
    }
  }
</script>
    <?php
}

function refresh_my_blog_table_function() {
    ?>
<script type='text/javascript'>
  function refreshTable() {
    clearTable();
    for (var i = 0; (cur_page.value - 1) * per_page.value + i < blogs.length && i < per_page.value; ++i){
      curBlog = blogs[(cur_page.value - 1) * per_page.value + i];
      addPersonnalBlogsRow(
        curBlog["uid"],
        curBlog["blogTitle"],
        curBlog["uploadDate"],
        curBlog["size"]
      );
    }
  }
</script>
    <?php
}
?>