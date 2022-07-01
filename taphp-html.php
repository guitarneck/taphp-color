<?php
if ( substr(php_sapi_name(),0,3) !== 'cli' || ! empty($_SERVER['DOCUMENT_ROOT']) )
   ob_start(function($buffer){
      $buffer = str_replace(PHP_EOL,"\n",$buffer);

      $patterns = array();
      $patterns[] = '/TAP (.*)/';
      $patterns[] = '/\n# (.*) # SKIP?(.*)/';
      $patterns[] = '/\nok (.*) # SKIP?(.*)/';
      $patterns[] = '/\nnot ok (.*) # SKIP?(.*)/';
      $patterns[] = '/\n# (.*) # TODO?(.*)/';
      $patterns[] = '/\nok (.*) # TODO?(.*)/';
      $patterns[] = '/\nnot ok (.*) # TODO?(.*)/';
      $patterns[] = '/\nok /';
      $patterns[] = '/\nnot ok /';
      $patterns[] = '/\n# tests (\d+) (.+)/';
      $patterns[] = '/\n# pass\s+(\d+)/';
      $patterns[] = '/\n# fail\s+(\d+)/';
      $patterns[] = '/\n# skip\s+(\d+)/';
      $patterns[] = '/\n# todo\s+(\d+)/';
      $patterns[] = '/\n# ok/';
      $patterns[] = '/\n\s+\-{3}\n/';
      $patterns[] = '/\n\s+\.{3}/';
      $patterns[] = '/\n# (.*)/';
      $patterns[] = '/\nBail out!(.*)/';
      $patterns[] = '/\n(\d+)..(\d+)/';
      $patterns[] = '/\n$/';

      $replacements = array();
      $replacements[] = '<span class="tap">TAP{HTML} $1</span>';
      $replacements[] = "\n\n".'<span style="color:#6ac"><i>$1 <strong class="in">skip</strong> $2</i></span>';
      $replacements[] = "\n".'<span style="color:#6ac;margin-left:1em"><i>✓ $1 <strong class="in">skip</strong> $2</i></span>';
      $replacements[] = "\n".'<span style="color:#6ac;margin-left:1em"><i>✗ $1 <strong class="in">skip</strong> $2</i></span>';
      $replacements[] = "\n\n".'<span style="color:#a86"><i>$1 <strong class="in">todo</strong> $2</i></span>';
      $replacements[] = "\n".'<span style="color:#a86;margin-left:1em"><i>✓ $1 <strong class="in">todo</strong> $2</i></span>';
      $replacements[] = "\n".'<span style="color:#a86;margin-left:1em"><i>✗ $1 <strong class="in">todo</strong> $2</i></span>';
      $replacements[] = "\n".'<span style="color:#0c0;margin-left:1em"><b>✓</b></span>&nbsp;';
      $replacements[] = "\n".'<span style="color:#c00;margin-left:1em"><b>✗</b></span>&nbsp;';
      $replacements[] = "  ".'<table><tr><th colspan="2">tests $1 $2</th></tr>';
      $replacements[] = "\n".'<tr><td>pass</td><td style="color:#0c0;text-align:right"><b>$1</b></td></tr>';
      $replacements[] = "\n".'<tr><td>fail</td><td style="color:#c00;text-align:right"><b>$1</b></td></tr>';
      $replacements[] = "\n".'<tr><td>skip</td><td style="color:#6ac;text-align:right"><b>$1</b></td></tr>';
      $replacements[] = "\n".'<tr><td>todo</td><td style="color:#ca6;text-align:right"><b>$1</b></td></tr>';
      $replacements[] = "\n".'<tr><td colspan="2" style="text-align:center"><br /><span class="ok">✓</span></td></tr>';
      $replacements[] = "\n".'<pre>';
      $replacements[] = "\n".'</pre>';
      $replacements[] = "\n\n".'<strong>$1</strong>';
      $replacements[] = "\n\n".'<span class="bail"><b>&nbsp;Bail out!$1&nbsp;</b></span>';
      $replacements[] = "\n\n".'<span class="tap">$1..$2</span>';
      $replacements[] = "\n".'</table>';

      $buffer = preg_replace($patterns, $replacements, $buffer);

      $buffer = preg_replace_callback('/(?<=<pre>)(.*?)(?=<\/pre>)/ms',function($m){return htmlentities($m[0]);}, $buffer);

      $buffer = str_replace("\n",'<br />',$buffer);

      header('Content-type: text/html; charset=utf-8');
      return  '<!DOCTYPE html>
               <html>
               <head>
               <meta charset="utf-8">
               <style>
                  body{line-height:1.25em;margin:0;padding:0;background:#f8f8ff;color:#446}
                  header,pre{overflow:hidden;margin:0;padding:0;}
                  header{background:#668;padding:.1em;margin-bottom:.4em;box-shadow:0px 2px 2px rgba(80,80,80,.6);position:sticky;top:0;height:2.4em}
                  header>button{fill:#aac;transition:fill 1s}
                  header>button:hover{fill:#ddf;transition:fill .4s;cursor:pointer}
                  header>button>svg>g{fill:inherit}
                  strong{color:#448;text-shadow:.05em .05em .05em rgba(0,0,0,.5)}
                  strong.in{color:inherit}
                  section{font-family:Calibri;font-weight:normal;margin-left:6em;color:#668}
                  pre{margin:.2em 0 -.8em 2.8em;padding:.1em 1.2em .1em 0;width:max-content;font-family:courier;font-size:.74em;line-height:.94em;color:#c0a;background:#fff8f8;border:1px solid #d44;border-radius:.5em}
                  span.ok{background:#0c0;color:#fff;padding:.15em .3em;border-radius:50%;font-weight:bold;text-shadow:.05em .05em .05em rgba(0,0,0,.5);box-shadow:.05em .05em .05em rgba(0,0,0,.5)}
                  span.tap{color:#88c;font-weight:bold;font-style:italic;text-shadow:-.02em -.02em .08em rgba(80,80,128,1)}
                  span.bail{background:#c05;color:#fde;padding:.2em;border:#f07 solid .1em;border-radius:.3em;text-shadow:.1em .1em .1em rgba(0,0,0,.3);box-shadow:.05em .05em .07em rgba(0,0,0,.5)}
                  th{border-bottom:1px solid #668;color:#668;text-shadow:.02em .02em .08em rgba(80,80,128,.6)}
               </style>
               </head>
               <body>
                  <header>
                     <button onclick="return window.history.back()" style="background:transparent;border:0;margin:.2em">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="32" height="32">
                              <g fill="#aac" transform="translate(512,512) rotate(180)">
                                 <path d="M256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm-28.9 143.6l75.5 72.4H120c-13.3 0-24 10.7-24 24v16c0 13.3 10.7 24 24 24h182.6l-75.5 72.4c-9.7 9.3-9.9 24.8-.4 34.3l11 10.9c9.4 9.4 24.6 9.4 33.9 0L404.3 273c9.4-9.4 9.4-24.6 0-33.9L271.6 106.3c-9.4-9.4-24.6-9.4-33.9 0l-11 10.9c-9.5 9.6-9.3 25.1.4 34.4z"/>
                              </g>
                           </svg>
                     </button>
                  </header>
                  <main>
                     <section>'.$buffer.'</section>
                  </main>
                  <br />
               </body>
               </html>';
   });