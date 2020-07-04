<?php

/* TODO: Ganze Datei kann gelöscht werden */

/**
 * SPAChat - Simple PHP Angular Ajax Chat
 *
 * @date    2013-05-31
 * @author  Jonas Sciangula Street <joni2back {at} gmail.com>
 */

namespace SPA_Common;
define('DB_USERNAME',       'root');
define('DB_PASSWORD',       '');
define('DB_HOST',           'localhost');
define('DB_NAME',           'em21');
define('CHAT_HISTORY',      '150');
define('CHAT_ONLINE_RANGE', '1');
define('ADMIN_USERNAME_PREFIX', 'Hannes');
session_start();

abstract class Model
{
    public $mysqli;

    public function __construct()
    {
        $this->mysqli = new \mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }
}

abstract class Controller
{
    private $_request, $_response, $_query, $_post, $_server, $_cookies;
    protected $_currentAction, $_defaultModel;

    const ACTION_POSTFIX = 'Action';
    const ACTION_DEFAULT = 'indexAction';

    public function __construct()
    {
        $this->_request  = &$_REQUEST;
        $this->_query    = &$_GET;
        $this->_post     = &$_POST;
        $this->_server   = &$_SERVER;
        $this->_cookies  = &$_COOKIE;
        $this->init();
    }

    public function init()
    {
        $this->dispatchActions();
        $this->render();
    }

    public function dispatchActions()
    {
        $action = $this->getQuery('action');
        if ($action && $action .= self::ACTION_POSTFIX) {
            if (method_exists($this, $action)) {
                $this->setResponse(
                    call_user_func(array($this, $action), array())
                );
            } else {
                $this->setHeader("HTTP/1.0 404 Not Found");
            }
        } else {
            $this->setResponse(
                call_user_func(array($this, self::ACTION_DEFAULT), array())
            );
        }
        return $this->_response;
    }

    public function render()
    {
        if ($this->_response) {
            if (is_scalar($this->_response)) {
                echo $this->_response;
            } else {
                throw new \Exception('Response content must be type scalar');
            }
            exit;
        }
    }

    public function indexAction()
    {
        return null;
    }

    public function setResponse($content)
    {
        $this->_response = $content;
    }

    public function setHeader($params)
    {
        if (! headers_sent()) {
            if (is_scalar($params)) {
                header($params);
            } else {
                foreach($params as $key => $value) {
                    header(sprintf('%s: %s', $key, $value));
                }
            }
        }
        return $this;
    }

    public function setModel($namespace)
    {
        $this->_defaultModel = $namespace;
        return $this;
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function setCookie($key, $value, $seconds = 3600)
    {
        $this->_cookies[$key] = $value;
        if (! headers_sent()) {
            setcookie($key, $value, time() + $seconds);
            return $this;
        }
    }

    public function getRequest($param = null, $default = null)
    {
        if ($param) {
            return isset($this->_request[$param]) ?
                $this->_request[$param] : $default;
        }
        return $this->_request;
    }

    public function getQuery($param = null, $default = null)
    {
        if ($param) {
            return isset($this->_query[$param]) ?
                $this->_query[$param] : $default;
        }
        return $this->_query;
    }

    public function getPost($param = null, $default = null)
    {
        if ($param) {
            return isset($this->_post[$param]) ?
                $this->_post[$param] : $default;
        }
        return $this->_post;
    }

    public function getServer($param = null, $default = null)
    {
        if ($param) {
            return isset($this->_server[$param]) ?
                $this->_server[$param] : $default;
        }
        return $this->_server;
    }

    public function getSession($param = null, $default = null)
    {
        if ($param) {
            return isset($this->_session[$param]) ?
                $this->_session[$param] : $default;
        }
        return $this->_session;
    }

    public function getCookie($param = null, $default = null)
    {
        if ($param) {
            return isset($this->_cookies[$param]) ?
                $this->_cookies[$param] : $default;
        }
        return $this->_cookies;
    }

    public function getModel()
    {
        if ($this->_defaultModel && class_exists($this->_defaultModel)) {
            return new $this->_defaultModel;
        }
    }

    public function sanitize($string, $quotes = ENT_QUOTES, $charset = 'utf-8')
    {
        return htmlentities($string, $quotes, $charset);
    }
}

abstract class Helper
{

}


namespace SPA_Chat;
use SPA_Common;
class Model extends SPA_Common\Model
{

    public function getMessages($limit = CHAT_HISTORY, $reverse = true)
    {
        $response = $this->mysqli->query("(SELECT * FROM wm18_messages
            ORDER BY `date` DESC LIMIT {$limit}) ORDER BY `date` ASC");

        $result = array();
        while($row = $response->fetch_object()) {
            $row->date = date("d.m.Y H:i:s", strtotime($row->date)). " Uhr";
            $result[] = $row;
        }
        $response->free();
        return $result;
    }

    public function addMessage($username, $message, $ip)
    {
        $response = $this->mysqli->query("SELECT profilbild FROM wm18_users WHERE username = '".$_SESSION['username']."'");
        while ($row = $response->fetch_object()) {
          $profilbild = $row->profilbild;
          $profilbild = addslashes($profilbild);
          if ($profilbild == "") {
            $profilbild = "nopic/Nopic.png";
          }
        }

        $username = addslashes($username);
        $message = addslashes($message);

        return (bool) $this->mysqli->query("INSERT INTO wm18_messages
            VALUES (NULL, '{$username}', '{$message}', '{$ip}', NOW(), '{$profilbild}')");
    }

    public function removeMessages()
    {
        return (bool) $this->mysqli->query("TRUNCATE TABLE wm18_messages");
    }

    public function removeOldMessages($limit = CHAT_HISTORY)
    {
        return (bool) $this->mysqli->query("DELETE FROM wm18_messages
            WHERE id NOT IN (SELECT id FROM wm18_messages
                ORDER BY date DESC LIMIT {$limit})");
    }

    public function getOnline($count = true, $timeRange = CHAT_ONLINE_RANGE)
    {
        if ($count) {
            $response = $this->mysqli->query("SELECT count(*) as total FROM wm18_chatOnline");
            return $response->fetch_object();
        }
        $response = $this->mysqli->query("SELECT ip FROM wm18_chatOnline");
        $result = array();
        while($row = $response->fetch_object()) {
            $result[] = $row;
        }
        $response->free();
        return $result;
    }

    public function updateOnline($hash, $ip)
    {
        return (bool) $this->mysqli->query("REPLACE INTO wm18_chatOnline
            VALUES ('{$hash}', '{$ip}', NOW())") or die(mysql_error());
    }

    public function clearOffline($timeRange = CHAT_ONLINE_RANGE)
    {
        return (bool) $this->mysqli->query("DELETE FROM wm18_chatOnline
            WHERE last_update <= (NOW() - INTERVAL {$timeRange} MINUTE)");
    }

    public function __destruct()
    {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

}

class Controller extends SPA_Common\Controller
{
    protected $_model;

    public function __construct()
    {
        $this->setModel('SPA_Chat\Model');
        parent::__construct();
    }

    public function indexAction()
    {
    }

    public function listAction()
    {
        $this->setHeader(array('Content-Type' => 'application/json'));
        $messages = $this->getModel()->getMessages();
        foreach($messages as &$message) {
            $message->me = $this->getServer('REMOTE_ADDR') === $message->ip;
        }
        return json_encode($messages);
    }

    public function saveAction()
    {
        $username = $this->getPost('username');                      // $this->getPost('username') - Dies war der Originalwert um den Usernamen frei zu bestimmen
        $message = $this->getPost('message');
        $ip = $this->getServer('REMOTE_ADDR');
        $this->setCookie('username', $username, 9999 * 9999);

        $result = array('success' => false);
        if ($username && $message) {
            $cleanUsername = preg_replace('/^'.ADMIN_USERNAME_PREFIX.'/', '', $username);
            $result = array(
                'success' => $this->getModel()->addMessage($cleanUsername, $message, $ip)
            );
        }

        if ($this->_isAdmin($username)) {
            $this->_parseAdminCommand($message);
        }

        $this->setHeader(array('Content-Type' => 'application/json'));
        return json_encode($result);
    }

    private function _isAdmin($username)
    {
        return preg_match('/^'.ADMIN_USERNAME_PREFIX.'/', $username);
    }

    private function _parseAdminCommand($message)
    {
        if ($message == '/clear') {
            $this->getModel()->removeMessages();
            return true;
        }
        if ($message == '/online') {
            $online = $this->getModel()->getOnline(false);
            $ipArr = array();
            foreach ($online as $item) {
                $ipArr[] = $item->ip;
            }
            $message = 'Online: ' . implode(", ", $ipArr);
            $this->getModel()->addMessage('Admin', $message, '0.0.0.0');
            return true;
        }
    }

    private function _getMyUniqueHash()
    {
        $unique  = $this->getServer('REMOTE_ADDR');
        $unique .= $this->getServer('HTTP_USER_AGENT');
        $unique .= $this->getServer('HTTP_ACCEPT_LANGUAGE');
        return md5($unique);
    }

    public function pingAction()
    {
        $ip = $this->getServer('REMOTE_ADDR');
        $hash = $this->_getMyUniqueHash();

        $this->getModel()->updateOnline($hash, $ip);
        $this->getModel()->clearOffline();
        $this->getModel()->removeOldMessages();

        $onlines = $this->getModel()->getOnline();

        $this->setHeader(array('Content-Type' => 'application/json'));
        return json_encode($onlines);
    }
}

$chatApp = new Controller(); ?>
