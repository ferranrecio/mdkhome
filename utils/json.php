<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Json editor</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

  <!-- Bootstrap core CSS -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <style type="text/css">
    html,
    body {
      height: 100%
    }

    textarea {
      width: 100%;
    }
  </style>
</head>

<body>
  <main role="main" class="container-fluid">

    <div class="starter-template">
      <div class="container-fuild">
        <div class="row h-100">
          <div class="col-sm jsoncol">
            <h2>Nomal</h2>
<textarea id="max">
{
  "name": "Moodle",
  "private": true,
  "description": "Moodle",
  "devDependencies": {
    "@babel/core": "7.4.5",
    "@babel/plugin-proposal-class-properties": "7.4.4",
    "@babel/plugin-proposal-json-strings": "7.2.0",
    "@babel/plugin-syntax-dynamic-import": "7.2.0",
    "@babel/plugin-syntax-import-meta": "7.2.0",
    "@babel/preset-env": "7.4.5",
    "ajv": "6.9.1",
    "async": "1.5.2",
    "babel-eslint": "10.0.1",
    "babel-plugin-system-import-transformer": "^4.0.0",
    "babel-plugin-transform-es2015-modules-amd-lazy": "2.0.1",
    "babel-preset-minify": "0.5.0",
    "eslint": "4.12.1",
    "eslint-plugin-babel": "5.3.0",
    "eslint-plugin-promise": "3.5.0",
    "fb-watchman": "2.0.0",
    "gherkin-lint": "1.1.3"
  },
  "engines": {
    "node": ">=8.9 <9"
  }
}
</textarea>
          </div>
          <div class="col-sm jsoncol">
            <h2>Minified</h2>
            <textarea id="min"></textarea>
          </div>
        </div>
      </div>
    </div>

  </main><!-- /.container -->

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script type="text/javascript">
    function escapeRegExp(string) {
      return string.replace(/[.*+\-?^${}()|[\]\\]/g, '\\$&');
    }

    document.addEventListener("DOMContentLoaded", function() {
      var nuheight = window.innerHeight - 100;
      console.log(nuheight);
      var textareas = document.querySelectorAll('textarea');
      textareas.forEach(function(textarea) {
        textarea.style.height = nuheight + 'px';
      });

      var maxTextarea = document.getElementById("max");
      var minTextarea = document.getElementById("min");

      maxTextarea.addEventListener('keyup', function() {
        try {
          var val = JSON.parse(this.value);
          minTextarea.value = JSON.stringify(val);
          minTextarea.style.backgroundColor = 'white';
        } catch (e) {
          minTextarea.style.backgroundColor = '#ddcccc';
        }
      });

      minTextarea.addEventListener('keyup', function() {
        try {
          var val = JSON.parse(this.value);
          maxTextarea.value = JSON.stringify(val, null, 2);
          maxTextarea.style.backgroundColor = 'white';
        } catch (e) {
          maxTextarea.style.backgroundColor = '#ddcccc';
        }
      });

      var h2Elements = document.getElementsByTagName("h2");
      for (var i = 0; i < h2Elements.length; i++) {
        h2Elements[i].addEventListener('click', function() {
          var mycol = this.parentNode;
          var jsoncolElements = document.getElementsByClassName('jsoncol');
          for (var j = 0; j < jsoncolElements.length; j++) {
            jsoncolElements[j].classList.toggle('col-sm');
            jsoncolElements[j].classList.toggle('d-none');
          }
          mycol.classList.remove('d-none');
          mycol.classList.add('col-sm');
        });
      }

      maxTextarea.addEventListener('keyup', function(e) {
        var keyCode = e.keyCode || e.which;
        var remove = e.shiftKey;
        if (keyCode == 9) {
          e.preventDefault();
          var start = this.selectionStart;
          var end = this.selectionEnd;
          var content = this.value;

          var tabval = ' '.repeat(2);

          document.execCommand('insertText', false, tabval);

          // var newcontent = content.substring(0, start)
          //             + "\t"
          //             + content.substring(end);
          // set textarea value to: text before caret + tab + text after caret
          // $(this).val(newcontent);

          // put caret at right position again
          // this.selectionStart =
          // this.selectionEnd = start + tabval.length;
        }
      });
    });
  </script>
  <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>
