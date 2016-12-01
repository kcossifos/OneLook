<?php


class OneLook
{

    private $conn;
    private $stats;
    private $emode;
    private $exname;
    public $asObj;

    private $defaults = array(
        'host'      => 'localhost',
        'user'      => 'root',
        'pass'      => '',
        'db'        => 'test',
        'port'      => NULL,
        'socket'    => NULL,
        'pconnect'  => FALSE,
        'charset'   => 'utf8',
        'errmode'   => 'error',
        'exception' => 'Exception',
        'asObj'     => true,
    );

    const RESULT_ASSOC = MYSQLI_ASSOC;
    const RESULT_NUM   = MYSQLI_NUM;

    function __construct($opt = array())
    {
        $opt = array_merge($this->defaults,$opt);

        $this->emode  = $opt['errmode'];
        $this->exname = $opt['exception'];
        $this->asObj = $opt['asObj'];

        if ($opt['pconnect'])
        {
            $opt['host'] = "p:".$opt['host'];
        }

        @$this->conn = mysqli_connect($opt['host'], $opt['user'], $opt['pass'], $opt['db'], $opt['port'], $opt['socket']);
        if ( !$this->conn )
        {
            die("Error: ".mysqli_connect_errno()." ".mysqli_connect_error());
        }

        mysqli_set_charset($this->conn, $opt['charset']) or $this->error(mysqli_error($this->conn));
        unset($opt);
    }

    public function query()
    {
        return $this->rawQuery($this->prepareQuery(func_get_args()));
    }

    public function ret($values)
    {
        if(!$values)
            return FALSE;

        if($this->asObj)
            return (object)$values;
        else
            return $values;
    }


    public function fetch($result,$mode=self::RESULT_ASSOC)
    {
        return mysqli_fetch_array($result, $mode);
    }


    public function affectedRows()
    {
        return mysqli_affected_rows ($this->conn);
    }

    public function insertId()
    {
        return mysqli_insert_id($this->conn);
    }

    public function numRows($result)
    {
        return mysqli_num_rows($result);
    }

    public function free($result)
    {
        mysqli_free_result($result);
    }


    public function getOne()
    {
        $query = $this->prepareQuery(func_get_args());
        if ($res = $this->rawQuery($query))
        {
            $row = $this->fetch($res);
            if (is_array($row)) {
                return $this->ret(reset($row));
            }
            $this->free($res);
        }
        return FALSE;
    }

    public function getRow()
    {
        $query = $this->prepareQuery(func_get_args());
        if ($res = $this->rawQuery($query)) {
            $ret = $this->fetch($res);
            $this->free($res);

            return $this->ret($ret);
        }
        return FALSE;
    }

    public function getCol()
    {
        $ret   = array();
        $query = $this->prepareQuery(func_get_args());
        if ( $res = $this->rawQuery($query) )
        {
            while($row = $this->fetch($res))
            {
                $ret[] = reset($row);
            }
            $this->free($res);
        }
        return $this->ret($ret);
    }

    public function getAll()
    {
        $ret   = array();
        $query = $this->prepareQuery(func_get_args());
        if ( $res = $this->rawQuery($query) )
        {
            while($row = $this->fetch($res))
            {
                $ret[] = $this->ret($row);
            }
            $this->free($res);
        }
        return $ret; 
    }

    public function getInd()
    {
        $args  = func_get_args();
        $index = array_shift($args);
        $query = $this->prepareQuery($args);

        $ret = array();
        if ( $res = $this->rawQuery($query) )
        {
            while($row = $this->fetch($res))
            {
                $ret[$row[$index]] = $row;
            }
            $this->free($res);
        }
        return $this->ret($ret);
    }

    public function getIndCol()
    {
        $args  = func_get_args();
        $index = array_shift($args);
        $query = $this->prepareQuery($args);

        $ret = array();
        if ( $res = $this->rawQuery($query) )
        {
            while($row = $this->fetch($res))
            {
                $keys = $row[$index];
                unset($row[$index]);
                $ret[$keys] = reset($row);
            }
            $this->free($res);
        }
        return $this->ret($ret);
    }

    public function parse()
    {
        return $this->prepareQuery(func_get_args());
    }

    public function whiteList($input,$allowed,$default=FALSE)
    {
        $found = array_search($input,$allowed);
        return ($found === FALSE) ? $default : $allowed[$found];
    }

    public function filterArray($input,$allowed)
    {
        foreach(array_keys($input) as $keys )
        {
            if ( !in_array($keys,$allowed) )
            {
                unset($input[$keys]);
            }
        }
        return $input;
    }


    public function lastQuery()
    {
        $last = end($this->stats);
        return $last['query'];
    }


    public function getStats()
    {
        return $this->stats;
    }

    private function rawQuery($query)
    {
        $start = microtime(TRUE);
        $res   = mysqli_query($this->conn, $query);
        $timer = microtime(TRUE) - $start;

        $this->stats[] = array(
            'query' => $query,
            'start' => $start,
            'timer' => $timer,
        );
        if (!$res)
        {
            $error = mysqli_error($this->conn);

            end($this->stats);
            $keys = key($this->stats);
            $this->stats[$keys]['error'] = $error;
            $this->cutStats();

            $this->error("$error. Full query: [$query]");
        }
        $this->cutStats();
        return $res;
    }

    private function prepareQuery($args)
    {
        $query = '';
        $raw   = array_shift($args);

        $array = preg_split('~(\?[nsiuap])|(:[A-Za-z0-9]+)~u'," ".$raw,null,PREG_SPLIT_DELIM_CAPTURE);
        $anum  = count($args);

        $array = array_values(array_filter($array));


        preg_match("~:([A-Za-z0-9]+)~u", $raw, $match);
        $named = count($match);
        if($named)
            $vals = array_shift($args);
            $vals = array();

        $pnum  = floor(count($array) / 2) - floor(count($vals)/2);

        if ( $pnum != $anum )
        {
            $this->error("Number of args ($anum) doesn't match number of placeholders ($pnum) in [$raw]");
        }

        foreach ($array as $i => $part)
        {
            if ( ($i % 2) == 0 )
            {
                $query .= $part;
                continue;
            }

            if(preg_match("~:([A-Za-z0-9]+)~u", $part, $match2))  {

                $query .= $this->escapeString($vals[$match2[1]]);
                continue;
            }

            $values = array_shift($args);


            switch ($part)
            {
                case '?n':
                    $part = $this->escapeIdent($values);
                    break;
                case '?s':
                    $part = $this->escapeString($values);
                    break;
                case '?i':
                    $part = $this->escapeInt($values);
                    break;
                case '?a':
                    $part = $this->createIN($values);
                    break;
                case '?u':
                    $part = $this->createSET($values);
                    break;
                case '?p':
                    $part = $values;
                    break;
            }

            $query .= $part;
        }

        return $query;
    }

    private function escapeInt($values)
    {
        if ($values === NULL)
        {
            return 'NULL';
        }
        if(!is_numeric($values))
        {
            $this->error("Integer (?i) placeholder expects numeric value, ".gettype($values)." given");
            return FALSE;
        }
        if (is_float($values))
        {
            $values = number_format($values, 0, '.', '');
        }
        return $values;
    }

    private function escapeString($values)
    {
        if ($values === NULL)
        {
            return 'NULL';
        }
        return  "'".mysqli_real_escape_string($this->conn,$values)."'";
    }

    private function escapeIdent($values)
    {
        if ($values)
        {
            return "`".str_replace("`","``",$values)."`";
        } else {
            $this->error("Empty value for identifier (?n) placeholder");
        }
    }

    private function createIN($data)
    {
        if (!is_array($data))
        {
            $this->error("Value for IN (?a) placeholder should be array");
            return;
        }
        if (!$data)
        {
            return 'NULL';
        }
        $query = $comma = '';
        foreach ($data as $values)
        {
            $query .= $comma.$this->escapeString($values);
            $comma  = ",";
        }
        return $query;
    }

    private function createSET($data)
    {
        if (!is_array($data))
        {
            $this->error("SET (?u) placeholder expects array, ".gettype($data)." given");
            return;
        }
        if (!$data)
        {
            $this->error("Empty array for SET (?u) placeholder");
            return;
        }
        $query = $comma = '';
        foreach ($data as $keys => $values)
        {
            $query .= $comma.$this->escapeIdent($keys).'='.$this->escapeString($values);
            $comma  = ",";
        }
        return $query;
    }

    private function error($err)
    {
        $err  = __CLASS__.": ".$err;

        if ( $this->emode == 'error' )
        {
            $err .= ". Error initiated in ".$this->caller().", thrown";

        } else {
            throw new $this->exname($err);
        }
    }

    private function caller()
    {
        $trace  = debug_backtrace();
        $caller = '';
        foreach ($trace as $t)
        {
            if ( isset($t['class']) && $t['class'] == __CLASS__ )
            {
                $caller = $t['file']." on line ".$t['line'];
            } else {
                break;
            }
        }
        return $caller;
    }


    private function cutStats()
    {
        if ( count($this->stats) > 100 )
        {
            reset($this->stats);
            $first = key($this->stats);
            unset($this->stats[$first]);
        }
    }
}
