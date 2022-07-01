<?php

class WindowsShells
{
   static
   function isCommand ()
   {
      return !empty($_SERVER['PROMPT']);
   }
   
   static
   function isPowerShell ()
   {
      return !empty($_SERVER['PATHEXT']) && strpos($_SERVER['PATHEXT'],'.CPL') !== false;
   }
   
   static
   function isGitBash ()
   {
      return !empty($_SERVER['MINGW_CHOST']);
   }
   
   static
   function isCommanderMini ()
   {
      return !empty($_SERVER['CMDER_ROOT']);
   }
   
   static
   function isVSCodeTerminal ()
   {
      return !empty($_SERVER['TERM_PROGRAM']) && $_SERVER['TERM_PROGRAM'] === 'vscode';
   }
}