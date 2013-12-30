
     先分割目录检测目录是否存在 不存在则创建 最后创建文件

        $folders = explode("/", $filename);
        foreach ($folders as $folder)
        {
            if (!empty($folder))
            {
                $parentPath = $path;
                $path .= "/" . $folder;
                
                if (!file_exists($path) && !strpos($folder, "."))
                {
                    if (@is_writable($parentPath))
                    {
                        mkdir($path);
                    }
                    else
                    {
                        return false;
                    }
                }
                else 
                {
                }
            }
        }
        
        $fp = fopen(realpath($config->general->upload->rootPath) . $filename, "w", "utf-8");
        fwrite($fp, $content);
        fclose($fp);
