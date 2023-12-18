<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Asynchronous Form</title>
    <style>
      #result {
        display: none;
      }
      .error {
        border: 1px solid red;
      }
      #spinner {
        display: none;
      }
    </style>
  </head>
  <body>
    <?php echo ini_get('error_log'); ?>

    <div id="measurements">
      <p>Enter measurements below to determine the total volume.</p>
      <form id="measurement-form" action="process_measurements.php" method="POST">
        Length: <input type="text" name="length" /><br />
        <br />
        Width: <input type="text" name="width" /><br />
        <br />
        Height: <input type="text" name="height" /><br />
        <br />
        <input id="html-submit" type="submit" value="Submit" />

        <!-- note dont make ajax button type submit -->
        <input id="ajax-submit" type="button" value="Ajax Submit" />
      </form>
    </div>

    <div id="spinner">
      <img src="spinner.gif" width="50" height="50" />
    </div>

    <div id="result">
      <p>The total volume is: <span id="volume"></span></p>
    </div>

    <script>

      var result_div = document.getElementById("result");
      var volume = document.getElementById("volume");
      let button = document.getElementById("ajax-submit");
      let origButtonValue = button.value;

      function showSpinner() {
        let spinner = document.getElementById("spinner");
        spinner.style.display = 'block';
      }

      function hideSpinner() {
        let spinner = document.getElementById("spinner");
        spinner.style.display = 'none';
      }

      
      function disableSubmitButton(){
        button.disabled = true;
        button.value = 'Loading...';
      }

      function enableSubmitButton(){
        button.disabled = false;
        button.value = origButtonValue;
      }

      // for each input, check if name is in errors array
      // add error class to element
      function displayErrors(errors){
        let inputs = document.getElementsByTagName('input');
        for(i=0; i< inputs.length; i++){
          let input = inputs[i];
          if(errors.indexOf(input.name) >= 0){
            input.classList.add('error');
          }
        }
      }

      function clearErrors(){
        let inputs = document.getElementsByTagName('input');
        for(i=0; i< inputs.length; i++){
          inputs[i].classList.remove('error');
        }
      }

      function postResult(value) {
        volume.innerHTML = value;
        result_div.style.display = 'block';
      }

      function clearResult() {
        volume.innerHTML = '';
        result_div.style.display = 'none';
      }

      // puts entered inputs into an array
      // omits textAreas, select-options, checkboxes, radio buttons
      // only works for input fields, not best option
      function gatherFormData(form){
        let inputs = form.getElementsByTagName('input');
        let array = [];
        for(i=0; i< inputs.length; i++){
          let inputNameValue = inputs[i].name + '=' + inputs[i].value;
          array.push(inputNameValue);
        }
        return array.join('&');
      }

      function calculateMeasurements() {
        clearResult();
        clearErrors();
        showSpinner();
        disableSubmitButton();
        

        var form = document.getElementById("measurement-form");
        let action = form.getAttribute('action');

        // determine form action
        // gather form data
        //let form_data = gatherFormData(form);
        let form_data = new FormData(form);
        for([key, value] of form_data.entries()){
          console.log(key + ': ' + value);
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', action, true);
        // remember to use getRequestHeader when using POST
        // do not set Content-type with FormData
        //xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);

            hideSpinner();
            enableSubmitButton();

            let json = JSON.parse(result);
            if(json.hasOwnProperty('errors') && json.errors.length > 0){
              console.log("we got errors");
              displayErrors(json.errors);
            } else {
              console.log("no errors");
              postResult(json.volume);
            }
          }
        };
        xhr.send(form_data);
      }

      button.addEventListener("click", calculateMeasurements);

    </script>

  </body>
</html>
