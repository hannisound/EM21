<?php

class Shoutbox {
    private $db;


    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USERNAME,DB_PASS, DB_NAME);
    }

    public function __destruct() {
        $this->db->close();
    }


    public function save($username, $message) {
        if(empty($username) || empty($message)) return false;
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
        //$message = nl2br($message);
        $result = false;
        $sql = 'INSERT INTO
                            em21_chat(username, message, ip, date)
                VALUES
                             (?, ?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssi', $username, $message, $ip);
        $stmt->execute();
        $result = $stmt->insert_id;
        $stmt->close();

        // Anzahl der Einträge Auslesen und danach für den User speichern
        $sql2 = "SELECT COUNT(ID) AS anzahl FROM em21_chat";
        $stmt = $this->db->prepare($sql2);
        $stmt->execute();
        $stmt->bind_result($anzahl);
        $stmt->fetch();
        $anzahl = $anzahl;
        $stmt->close();

        $sql3 = 'UPDATE em21_users SET last_chat_message = ? WHERE username = ?';
        $stmt = $this->db->prepare($sql3);
        $stmt->bind_param('is', $anzahl, $username);
        $stmt->execute();
        $stmt->close();

        // Ab hier geht es normal weiter
        if(!$result) return false;

        $result = array('id' => $result,
                'username'  => htmlspecialchars($username),
                'message'   => nl2br(htmlspecialchars($message)),
                'dateraw'   => date('Y-m-d h:m:s'));

        return $result;
    }


    public function load() {
        $result = false;
        $sql = 'SELECT
                        id,
                        username,
                        message,
                        DATE_FORMAT(date, "%d.%m.%Y - %k:%i:%s"),
                        date as dateraw
                FROM
                        em21_chat
                ORDER BY
                        date DESC
                         ';
        $stmt = $this->db->prepare($sql);

        $stmt->execute();
        $stmt->bind_result($id, $username, $message, $date, $dateraw);
        while($stmt->fetch()) {
            $result[] = array('ID'          => $id,
                              'Username'    => htmlspecialchars($username),
                              'Message'     => nl2br(htmlspecialchars($message)),
                              'Date'        => $date,
                              'DateRaw'     => $dateraw);
        }
        $stmt->close();
        return $result;
    }

	public function getNewEntries($date) {
    $result = false;
    $sql = 'SELECT
                    id,
                    username,
                    message,
                    DATE_FORMAT(date, "%d.%m.%Y"),
                    date as dateraw
            FROM
                    em21_chat
            WHERE
                    date > ?
            ORDER BY
                    date DESC
            LIMIT
                     1';
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $stmt->bind_result($id, $username, $message, $date, $dateraw);
    while($stmt->fetch()) {
        $result = array('id'            => $id,
                          'username'    => htmlspecialchars($username),
                          'message'     => nl2br(htmlspecialchars($message)),
                          'date'        => $date,
                          'dateraw'     => $dateraw);
    }
    $stmt->close();
    return $result;
  }
}
