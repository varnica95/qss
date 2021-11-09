<?php

namespace Qss\Core;


class View
{
    /**
     * Method for rendering file and data
     *
     * @param [type] $path
     * @param array $data
     * @return void
     */
    public function render(string $path, array $data = [])
    {       
        $part = str_replace(".", "\\", $path);
        $file = $this->getFile($part);
       
        $documentName = ucfirst(explode(".", $path)[0]);

        array_push($data, $documentName, $file);

        extract($data, EXTR_SKIP);
       
        ob_start();
        try {
            extract($data, EXTR_SKIP);
            require $this->getFile("app");
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        return $output;
    }

    /**
     * Getting a file with full path
     *
     * @return void
     */
    private function getFile(string $part)
    {
        
        $file = dirname(__DIR__) . "\\..\\resources\\views\\{$part}.phtml";
     
        if(!is_readable($file) || !is_file($file)){
            throw new \Exception("Cannot read {$file}");
        }

        return $file;
    }

}