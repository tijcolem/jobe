<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ==============================================================
 *
 * C++
 *
 * ==============================================================
 *
 * @copyright  2014 Richard Lobb, University of Canterbury
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('application/libraries/LanguageTask.php');

class Rust_Task extends Task {

    public function __construct($source, $filename, $input, $params) {
        Task::__construct($source, $filename, $input, $params);
        $this->default_params['compileargs'] = array(
            '-v');
    }

    public static function getVersionCommand() {
        return array('rustc --version', '/rustc ([0-9]+.[0-9]+\.[0-9])(.*)/');
    }

    public function compile() {
        $src = basename($this->sourceFileName);
        $errorFileName = "$src.err";
        $execFileName = "$src.exe";
        $compileargs = $this->getParam('compileargs');
        $linkargs = $this->getParam('linkargs');
        $cmd = "rustc " . implode(' ', $compileargs) . " -o $execFileName $src " . "  2>$errorFileName";
        exec($cmd, $output, $returnVar);
        if ($returnVar == 0) {
            $this->cmpinfo = '';
            $this->executableFileName = $execFileName;
        }
        else {
            $this->cmpinfo = file_get_contents($errorFileName);
        }
    }

    // A default name for rust programs
    public function defaultFileName($sourcecode) {
        return 'prog.rs';
    }


    // The executable is the output from the compilation
    public function getExecutablePath() {
        return "./" . $this->executableFileName;
    }


    public function getTargetFile() {
        return '';
    }
};
