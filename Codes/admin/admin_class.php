<?php
session_start();
ini_set('display_errors', 1);
class Action
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
    }
    function __destruct()
    {
        $this
            ->db
            ->close();
        ob_end_flush();
    }

    function login()
    {
        extract($_POST);
        $qry = $this
            ->db
            ->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
        if ($qry->num_rows > 0)
        {
            foreach ($qry->fetch_array() as $key => $value)
            {
                if ($key != 'passwors' && !is_numeric($key)) $_SESSION['login_' . $key] = $value;
            }
            return 1;
        }
        else
        {
            return 3;
        }
    }
    function logout()
    {
        session_destroy();
        foreach ($_SESSION as $key => $value)
        {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function save_user()
    {
        extract($_POST);
        $data = " name = '$name' ";
        $data .= ", username = '$username' ";
        if (!empty($password)) $data .= ", password = '" . md5($password) . "' ";
        $data .= ", type = '$type' ";
        $data .= ", window_id = '$window_id' ";
        $chk = $this
            ->db
            ->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
        if ($chk > 0)
        {
            return 2;
            exit;
        }
        if (empty($id))
        {
            $save = $this
                ->db
                ->query("INSERT INTO users set " . $data);
        }
        else
        {
            $save = $this
                ->db
                ->query("UPDATE users set " . $data . " where id = " . $id);
        }
        if ($save)
        {
            return 1;
        }
    }
    function delete_user()
    {
        extract($_POST);
        $delete = $this
            ->db
            ->query("DELETE FROM users where id = " . $id);
        if ($delete) return 1;
    }
    function signup()
    {
        extract($_POST);
        $data = " name = '$name' ";
        $data .= ", contact = '$contact' ";
        $data .= ", address = '$address' ";
        $data .= ", username = '$email' ";
        $data .= ", password = '" . md5($password) . "' ";
        $data .= ", type = 3";
        $chk = $this
            ->db
            ->query("SELECT * FROM users where username = '$email' ")->num_rows;
        if ($chk > 0)
        {
            return 2;
            exit;
        }
        $save = $this
            ->db
            ->query("INSERT INTO users set " . $data);
        if ($save)
        {
            $qry = $this
                ->db
                ->query("SELECT * FROM users where username = '" . $email . "' and password = '" . md5($password) . "' ");
            if ($qry->num_rows > 0)
            {
                foreach ($qry->fetch_array() as $key => $value)
                {
                    if ($key != 'passwors' && !is_numeric($key)) $_SESSION['login_' . $key] = $value;
                }
            }
            return 1;
        }
    }

    function save_transaction()
    {
        extract($_POST);
        $data = " name = '$name' ";
        $cwhere = '';
        if (!empty($id))
        {
            $cwhere = " and id != $id ";
        }
        $chk = $this
            ->db
            ->query("SELECT * FROM transactions where " . $data . $cwhere)->num_rows;
        if ($chk > 0)
        {
            return 2;
            exit;
        }
        if (empty($id))
        {
            $save = $this
                ->db
                ->query("INSERT INTO transactions set " . $data);
        }
        else
        {
            $save = $this
                ->db
                ->query("UPDATE transactions set " . $data . " where id=" . $id);
        }
        if ($save) return 1;
    }
    function delete_transaction()
    {
        extract($_POST);
        $delete = $this
            ->db
            ->query("DELETE FROM transactions where id = " . $id);
        if ($delete) return 1;
    }

    function save_window()
    {
        extract($_POST);
        $data = " name = '$name' ";
        $data .= ", transaction_id = '$transaction_id' ";
        $cwhere = '';
        if (!empty($id))
        {
            $cwhere = " and id != $id ";
        }
        $chk = $this
            ->db
            ->query("SELECT * FROM transaction_windows where name = '$name' and transaction_id = '$transaction_id' " . $cwhere)->num_rows;
        if ($chk > 0)
        {
            return 2;
            exit;
        }
        if (empty($id))
        {
            $save = $this
                ->db
                ->query("INSERT INTO transaction_windows set " . $data);
        }
        else
        {
            $save = $this
                ->db
                ->query("UPDATE transaction_windows set " . $data . " where id=" . $id);
        }
        if ($save) return 1;
    }
    function delete_window()
    {
        extract($_POST);
        $delete = $this
            ->db
            ->query("DELETE FROM transaction_windows where id = " . $id);
        if ($delete) return 1;
    }

    function save_queue()
    {
        extract($_POST);
        $data = " name = '$name' ";
        $data = " phone_number = '$phone_number' ";
        $data .= ", transaction_id = '$transaction_id' ";
        $queue_no = 1001;
        $chk = $this
            ->db
            ->query("SELECT * FROM queue_list where transaction_id = $transaction_id and date(date_created) = '" . date("Y-m-d") . "' ")->num_rows;
        if ($chk > 0)
        {
            $queue_no += $chk;
        }
        $data .= ", queue_no = '$queue_no' ";

        $save = $this
            ->db
            ->query("INSERT INTO queue_list set " . $data);

        if ($save) $lastId = $this
            ->db->insert_id;
        $query = $this
            ->db
            ->query("SELECT queue_no FROM queue_list where id =  " . $lastId);
        foreach ($query->fetch_array() as $key => $value)
        {
            $queueNo = $value;
        }
        return $queueNo;
    }
    function get_queue()
    {
        extract($_POST);
        $query = $this
            ->db
            ->query("SELECT q.*,t.name as wname FROM queue_list q inner join transaction_windows t on t.id = q.window_id where date(q.date_created) = '" . date('Y-m-d') . "' and q.transaction_id = '$id' and q.status = 1 order by q.id desc limit 1 ");
        if ($query->num_rows > 0)
        {
            foreach ($query->fetch_array() as $key => $value)
            {
                if (!is_numeric($key)) $data[$key] = $value;
            }
            return json_encode(array(
                'status' => 1,
                "data" => $data
            ));
        }
        else
        {
            return json_encode(array(
                'status' => 0
            ));

        }
    }

    function update_queue()
    {
        $tid = $this
            ->db
            ->query("SELECT * FROM transaction_windows where id =" . $_SESSION['login_window_id'])->fetch_array() ['transaction_id'];
        $this
            ->db
            ->query("UPDATE queue_list set  window_id = '" . $_SESSION['login_window_id'] . "' where transaction_id = '$tid' and  date(date_created) = '" . date('Y-m-d') . "' and status=0 order by id asc limit 1");
        $smsquery = $this
            ->db
            ->query("SELECT q.*,t.name as wname FROM queue_list q inner join transaction_windows t on t.id = q.window_id where date(q.date_created) = '" . date('Y-m-d') . "' and q.window_id = '" . $_SESSION['login_window_id'] . "' and q.status = 0 order by q.id desc limit 1  ");
        if ($smsquery->num_rows > 0)
        {

            $this
                ->db
                ->query("UPDATE queue_list set status = 1 , window_id = '" . $_SESSION['login_window_id'] . "' where transaction_id = '$tid' and  date(date_created) = '" . date('Y-m-d') . "' and status=0 order by id asc limit 1");

            $query = $this
                ->db
                ->query("SELECT q.*,t.name as wname FROM queue_list q inner join transaction_windows t on t.id = q.window_id where date(q.date_created) = '" . date('Y-m-d') . "' and q.window_id = '" . $_SESSION['login_window_id'] . "' and q.status = 1 order by q.id desc limit 1  ");
            if ($query->num_rows > 0)
            {
                foreach ($query->fetch_array() as $key => $value)
                {
                    if (!is_numeric($key)) $data[$key] = $value;
                }
                return json_encode(array(
                    'status' => 1,
                    "data" => $data
                ));
            }
            else
            {
                return json_encode(array(
                    'status' => 0
                ));

            }
        }
        else
        {
            $data['queue_no'] = 'Done';
            return json_encode(array(
                'status' => 1,
                "data" => $data
            ));
        }
    }

}

