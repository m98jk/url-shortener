<?php
class MainDB
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllCert()
    {
        $query = "SELECT id,title,st,pnum, created_at  FROM workshops where isDelt != 1";
        $this->db->query($query);
        return $this->db->resultSet();
    }
    public function addCert($data)
    {
        try {

            $this->db->query('INSERT INTO tceworkshops (title,tp,sdate,edate) VALUES(:title, :tp,:sdate,:edate)');
            // Bind values
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':tp', $data['tp']);
            $this->db->bind(':sdate', $data['sdate']);
            $this->db->bind(':edate', $data['edate']);
            // Execute and get lastInsertId
            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
            die('Error in adding user ' . $e->getMessage());
        }
    }
    public function addRel($pId, $wrId)
    {
        $this->db->query('INSERT IGNORE INTO crtlink (pId, wrId) VALUES(:pId, :wrId)');
        // Bind values
        $this->db->bind(':pId', $pId);
        $this->db->bind(':wrId', $wrId);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // change the status
    public function status($table, $id)
    {
        $this->db->query("UPDATE $table  SET st = CASE WHEN st = 1 THEN 0 ELSE 1 END WHERE id = :id");
        // Bind values
        $this->db->bind(':id', $id);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function delete($table, $id)
    {
        $this->db->query("UPDATE $table SET isDelt = 1 WHERE id = :id ");
        // Bind values
        $this->db->bind(':id', $id);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function dleteUserCrt($crtId, $id)
    {
        $this->db->query("UPDATE crtlink SET wrId =:wr WHERE  wrId=:wrId and pid = :id ");
        // Bind values
        $this->db->bind(':wr', "d_" . $crtId);
        $this->db->bind(':wrId', $crtId);
        $this->db->bind(':id', $id);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // add attendees
    public function getCertAttendee($start, $len, $search)
    {
        $str = $search ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%' " : "";
        $query =  "SELECT att.name,att.id,

GROUP_CONCAT(workshops.title,'_',workshops.id) as allcert
FROM attendees as att JOIN crtlink ON att.id = crtlink.pId 
JOIN workshops ON workshops.id = crtlink.wrId WHERE att.isDelt != 1 
AND workshops.isDelt != 1 $str GROUP BY att.id ORDER BY att.id DESC limit :srt,:lmt
        ";
        $this->db->query($query);
        $this->db->bind(':srt', $start, PDO::PARAM_INT);
        $this->db->bind(':lmt', $len, PDO::PARAM_INT);
        $res = $this->db->resultset();
        return $res;
    }
    public function addAttendee($data, $wrId)
    {
        $this->db->query(
            'INSERT INTO tceattendees (name, email,wid) 
            VALUES(:name, :email,:wid)
            ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);
            '
        );
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':wid', $wrId);
        // Execute and get lastInsertId
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // update cert xy and width
    public function updateCert($data)
    {
        $this->db->query('UPDATE tceworkshops SET xlsx=:xlsx ,pnum=:pnum WHERE id=:id');
        // Bind values
        $this->db->bind(':xlsx', $data["xlsx"]);
        $this->db->bind(':pnum', $data["pnum"]);
        $this->db->bind(':id', $data["id"]);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // user cert
    // public function getTitleCert()
    // {
    //     $this->db->query(
    //         "SELECT id,title FROM workshops where isDelt != 1 and st != 0"
    //     );
    //     return $this->db->resultSet();
    // }

    // public function getCertInfo($email, $id)
    // {
    //     $this->db->query(
    //         "SELECT att.name,att.email,
    //         wr.pdf,
    //         REPLACE( wr.title, ' ', '_') AS title,
    //         wr.x,
    //         wr.y,
    //         wr.fs
    //     FROM attendees as att
    //         JOIN crtlink ON att.id = crtlink.pId
    //         JOIN workshops as wr ON wr.id = crtlink.wrId
    //     WHERE att.email = :email
    //         AND wr.id = :id AND wr.st!=0 AND wr.isDelt != 1
    //         ORDER BY wr.id DESC
    //     "
    //     );
    //     $this->db->bind(':email', $email);
    //     $this->db->bind(':id', $id);
    //     return $this->db->single();
    // }
    public function getCrtJson($start, $len, $search)
    {
        $str = $search ? " AND title LIKE '%$search%'  " : "";
        $query =  "SELECT id,TO_BASE64(id) as token,title,tp,st,pnum,created_at,sdate,edate  FROM tceworkshops where isDelt != 1  $str  ORDER BY id DESC limit :srt,:lmt  ";
        $this->db->query($query);
        $this->db->bind(':srt', $start, PDO::PARAM_INT);
        $this->db->bind(':lmt', $len, PDO::PARAM_INT);
        $res = $this->db->resultset();
        return $res;
    }
    public function updateCertInfo($name, $val, $id)
    {
        $query = "UPDATE  tceworkshops SET $name=:val WHERE id=:id";
        $this->db->query($query);
        $this->db->bind(':val', $val);
        $this->db->bind(':id', $id);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function updateSurveyInfo($name, $val, $id)
    {
        $query = "UPDATE  tcesurvey SET $name=:val WHERE id=:id";
        $this->db->query($query);
        $this->db->bind(':val', $val);
        $this->db->bind(':id', $id);
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function attendData($id)
    {
        $query =  "SELECT `name`  FROM tceattendees where isDelt != 1 and wid=:id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        $res = $this->db->resultset();
        return $res;
    }
    public function iTotalRecords($table)
    {
        $query =  "SELECT id  FROM $table where isDelt != 1";
        $this->db->query($query);
        $_ = $this->db->single();
        return $this->db->rowCount();
    }

    // survey
    public function addSurvey($data)
    {

        $query = 'INSERT INTO tcesurvey (qs, tp,opt,ref) VALUES(:qs, :tp,:opt,:ref)';
        $this->db->query($query);
        // Bind values
        $this->db->bind(':ref', $data['id']);
        $this->db->bind(':qs', $data['qs']);
        $this->db->bind(':tp', $data['tp']);
        $opt = isset($data['opt']) ? $data['opt'] : null;

        $this->db->bind(':opt', $opt);
        // Execute and get lastInsertId
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getSurvey($start, $len, $search, $id)
    {

        $str = $search ? " AND qs LIKE '%$search%'  " : "";

        $query =  "SELECT *  FROM tcesurvey where ref=:id and isDelt != 1  $str  ORDER BY id DESC limit :srt,:lmt  ";
        $this->db->query($query);
        $this->db->bind(':srt', $start, PDO::PARAM_INT);
        $this->db->bind(':lmt', $len, PDO::PARAM_INT);
        $this->db->bind(':id', $id);
        $res = $this->db->resultset();
        return $res;
    }
    public function showSurvey($id)
    {
        $query =  "SELECT id,qs,opt,tp  FROM tcesurvey where ref=:id and isDelt != 1   ORDER BY id DESC ";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        $res = $this->db->resultset();
        return $res;
    }
    public function addSurveyAnswers($answers,$surveyid)
    {

        $query = 'INSERT INTO tcesurveyans (ans,ref) VALUES(:ans,:ref)';
        $this->db->query($query);
        // Bind values
        $this->db->bind(':ref', $surveyid);
        $this->db->bind(':ans', $answers);
        // Execute and get lastInsertId
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getResultSurvey($start, $len, $search, $id)
    {

        $str = $search ? " AND qs LIKE '%$search%'  " : "";

        $query =  "SELECT *,TO_BASE64(id) as token  FROM tcesurveyans where ref=:id and isDelt != 1  $str  ORDER BY id DESC limit :srt,:lmt  ";
        $this->db->query($query);
        $this->db->bind(':srt', $start, PDO::PARAM_INT);
        $this->db->bind(':lmt', $len, PDO::PARAM_INT);
        $this->db->bind(':id', $id);
        $res = $this->db->resultset();
        return $res;
    }

}