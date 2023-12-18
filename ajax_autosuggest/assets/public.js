// note: IE8 doesn't support DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {

  var suggestions = document.getElementById("suggestions");
  var form = document.getElementById("search-form");
  var search = document.getElementById("search");

  function showSuggestions(json) {
    suggestions.style.display = 'block';
  }

  function getSuggestions() {
    var q = search.value;

    if(q.length < 3){
      suggestions.style.display = 'none';
      return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'autosuggest.php?q=' + q, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result: ' + result);
        result = '{}';
        var json = JSON.parse(result);
        showSuggestions(json);
      }
    };
    xhr.send();
  }

  // use 'input' (every key)
  search.addEventListener("input", getSuggestions, false);

});
