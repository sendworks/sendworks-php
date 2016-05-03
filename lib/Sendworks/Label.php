<?php
namespace Sendworks;

class Label
{
    protected $connection;
    public $filename;
    public $content;

    public function __construct($data, $connection)
    {
        $this->connection = $connection;
        $this->filename = $data['filename'];
        $this->content = $data['content'];
    }

    public function write($destination)
    {
        file_put_contents($destination, $this->content);
    }

    public static function import($mixed, $connection = null)
    {
        if (is_array($mixed)) {
            return new self($mixed, $connection);
        }
        return new LabelRef($mixed, $connection);
    }
}
