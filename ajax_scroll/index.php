<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Infinite Scroll</title>
    <style>
      #blog-posts {
        width: 700px;
      }
      .blog-post {
        border: 1px solid black;
        margin: 10px 10px 20px 10px;
        padding: 6px 10px;
      }
      #spinner {
        display: none;
      }
    </style>
  </head>
  <body>
    <div id="blog-posts">
    </div>

    <div id="spinner">
      <img src="spinner.gif" width="50" height="50" />
    </div>

    <div id="load-more-container">
      <button id="load-more" data-page="0">Load more</button>
    </div>

    <script>

      var container = document.getElementById('blog-posts');
      var load_more = document.getElementById('load-more');
      let request_in_progress = false;

      function showSpinner() {
        var spinner = document.getElementById("spinner");
        spinner.style.display = 'block';
      }

      function hideSpinner() {
        var spinner = document.getElementById("spinner");
        spinner.style.display = 'none';
      }

      function showLoadMore() {
        load_more.style.display = 'inline';
      }

      function hideLoadMore() {
        load_more.style.display = 'none';
      }

      // put new html into a temp div
      // this causes browser to parse it as elements.
      function appendToDiv(div, newHTML){
        let temp = document.createElement('div');
        temp.innerHTML = newHTML;

        // find and work with those elements
        // use firstElementChild because of how DOM treats whitespace
        let class_name = temp.firstElementChild.className;
        let items = temp.getElementsByClassName(class_name);

        let len = items.length;
        for(i=0; i<len; i++){
          div.appendChild(items[0]);
        }
      }

      function setCurrentPage(page){
        console.log('Incrementing page to: ' + page);
        load_more.setAttribute('data-page', page);
      }

      // INFINITE  SCROLLING !!
      function scrollReaction() {
        let content_height = container.offsetHeight;
        let current_y = window.innerHeight + window.pageYOffset;
        console.log(current_y + '/' + content_height);
        if(current_y >= content_height){
          loadMore();
        }
      }

      function loadMore() {

        if(request_in_progress){
          return;
        }
        request_in_progress = true;

        showSpinner();
        hideLoadMore();

        let page = parseInt(load_more.getAttribute('data-page'));
        let next_page = page + 1;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'blog_posts.php?page=' + next_page, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);

            hideSpinner();
            setCurrentPage(next_page);
            // append results to end of blog posts
            appendToDiv(container,result);
            showLoadMore();
            request_in_progress = false;

          }
        };
        xhr.send();
      }

      load_more.addEventListener("click", loadMore);

      window.onscroll = function() {
        scrollReaction();
      }

      // Load even the first page with Ajax
      loadMore();
    </script>

  </body>
</html>
