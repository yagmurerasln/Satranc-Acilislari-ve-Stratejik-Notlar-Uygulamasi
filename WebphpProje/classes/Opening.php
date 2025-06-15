<?php
class Opening {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllOpenings() {
        $sql = "SELECT o.*, u.username as creator FROM openings o 
                JOIN users u ON o.created_by = u.id 
                ORDER BY o.created_at DESC";
        $result = $this->db->query($sql);
        
        $openings = [];
        while ($row = $result->fetch_assoc()) {
            $openings[] = $row;
        }
        
        return $openings;
    }
    
    public function getOpeningById($id) {
        $id = $this->db->escape($id);
        $sql = "SELECT o.*, u.username as creator FROM openings o 
                JOIN users u ON o.created_by = u.id 
                WHERE o.id = '$id'";
        $result = $this->db->query($sql);
        
        return $result->fetch_assoc();
    }
    
    public function addOpening($name, $moves, $description, $userId) {
        $name = $this->db->escape($name);
        $moves = $this->db->escape($moves);
        $description = $this->db->escape($description);
        $userId = $this->db->escape($userId);
        
        $sql = "INSERT INTO openings (name, moves, description, created_by) 
                VALUES ('$name', '$moves', '$description', '$userId')";
        
        return $this->db->query($sql);
    }
    
    public function updateOpening($id, $name, $moves, $description) {
        $id = $this->db->escape($id);
        $name = $this->db->escape($name);
        $moves = $this->db->escape($moves);
        $description = $this->db->escape($description);
        
        $sql = "UPDATE openings SET 
                name = '$name',
                moves = '$moves',
                description = '$description'
                WHERE id = '$id'";
        
        return $this->db->query($sql);
    }
    
    public function deleteOpening($id) {
        $id = $this->db->escape($id);
        $sql = "DELETE FROM openings WHERE id = '$id'";
        return $this->db->query($sql);
    }
    
    public function getCommentsByOpeningId($openingId) {
        $openingId = $this->db->escape($openingId);
        $sql = "SELECT c.*, u.username FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.opening_id = '$openingId' 
                ORDER BY c.created_at DESC";
        $result = $this->db->query($sql);
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        
        return $comments;
    }
    
    public function addComment($openingId, $userId, $content) {
        $openingId = $this->db->escape($openingId);
        $userId = $this->db->escape($userId);
        $content = $this->db->escape($content);
        
        $sql = "INSERT INTO comments (opening_id, user_id, content) 
                VALUES ('$openingId', '$userId', '$content')";
        
        return $this->db->query($sql);
    }
}