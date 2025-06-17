<?php
class sessionManager {
    private $timeout = 900; // Tiempo de expiración en segundos (15 min)

    public function __construct($timeout = 900) {
        $this->timeout = $timeout;
        session_start();
        $this->checkSessionTimeout();
    }

    public function login($userId, $userName) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $userName;
        $_SESSION['last_activity'] = time();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    private function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $this->timeout) {
                $this->logout();
                header("Location: ../views/auth/login.php?timeout=1");
                exit;
            } else {
                $_SESSION['last_activity'] = time(); // Renueva la actividad
            }
        }
    }
}
?>
