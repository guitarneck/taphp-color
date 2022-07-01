<?php
if ( substr(php_sapi_name(),0,3) === 'cli' && empty($_SERVER['DOCUMENT_ROOT']) )
   ob_start(function($buffer){
      $buffer = str_replace(PHP_EOL,"\n",$buffer);

      if ( strtolower(substr(PHP_OS,0,3)) == 'win' ):

         include_once 'WindowsShells.class.php';

         switch ( true ):

            case WindowsShells::isVSCodeTerminal():
               define('ticks', '✔');
               define('cross', '✘');
               break;

            case WindowsShells::isCommanderMini():
               define('ticks', '✓');
               define('cross', '✗');
               break;

            case WindowsShells::isCommand():
               define('ticks', '>');
               define('cross', 'x');
               break;

            case WindowsShells::isPowerShell():
               define('ticks', '>');
               define('cross', 'x');
               break;

            case WindowsShells::isGitBash():
               define('ticks', '✓');
               define('cross', '✗');
               break;

            default:
               define('ticks', '>');
               define('cross', 'x');

         endswitch;

      else:

         define('ticks', '✔'); // ✓
         define('cross', '✘'); // ✗

      endif;

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
      $replacements[] = "\033[90mTAP $1\033[0m";
      $replacements[] = "\n\n\033[36m$1 \033[1;36mskip\033[0m \033[36m$2\033[0m";
      $replacements[] = "\n  \033[1;32m{ticks}\033[0m \033[36m$1 \033[1;96mskip\033[0m \033[36m$2\033[0m";
      $replacements[] = "\n  \033[1;31m{cross}\033[0m \033[36m$1 \033[1;96mskip\033[0m \033[36m$2\033[0m";
      $replacements[] = "\n\n\033[33m$1 \033[1;33mtodo\033[0m \033[33m$2\033[0m";
      $replacements[] = "\n  \033[1;32m{ticks}\033[0m \033[33m$1\033[0m \033[1;93mtodo\033[0m \033[33m$2\033[0m";
      $replacements[] = "\n  \033[1;31m{cross}\033[0m \033[33m$1\033[0m \033[1;93mtodo\033[0m \033[33m$2\033[0m";
      $replacements[] = "\n  \033[1;32m{ticks}\033[0m \033[90m";
      $replacements[] = "\n  \033[1;31m{cross}\033[0m \033[90m";
      $replacements[] = "\n\n      \033[4mtests $1 $2\033[0m";
      $replacements[] = "\n      pass      \033[1;32m$1\033[0m";
      $replacements[] = "\n      fail      \033[1;31m$1\033[0m";
      $replacements[] = "\n      skip      \033[1;36m$1\033[0m";
      $replacements[] = "\n      todo      \033[1;33m$1\033[0m";
      $replacements[] = "\n\n            \033[1;37;42m {ticks} \033[0m";
      $replacements[] = "\n\033[35m";
      $replacements[] = "\n\033[0m";
      $replacements[] = "\n\n\033[1;37m$1\033[0m";
      $replacements[] = "\n\n\033[37;41m Bail out!$1 \033[0m";
      $replacements[] = "\n\n\033[90m$1..$2\033[0m";
      $replacements[] = "\033[0m\n";

      $buffer = preg_replace($patterns, $replacements, $buffer);

      $buffer = str_replace(array('{ticks}','{cross}'),array(ticks,cross),$buffer);

      return $buffer;
   });