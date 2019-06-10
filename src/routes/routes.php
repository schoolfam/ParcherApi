<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// user api

// post queries
// login
$app->post('/api/user/login/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $email = $data['email']; $password = $data['password'];
    
    $sql = "select count(*) as total,roleId from user where emailAddress= '".$email."' and password='".$password."';";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($result);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});




// get users
$app->get('/api/user/', function(Request $request, Response $response){
    $sql = "SELECT* FROM users";
    // echo 'getting partner';
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single user
$app->post('/api/user/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT* FROM users where id = ".$id."";
    // echo 'getting partner';
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put queries
$app->put('/api/user/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $username = $data["username"];
    $id = $data["id"];
    
    $sql = "update users set username = :username where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['username'=>$username, 'id'=>$id]);

        echo '{"message":"username updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete user queries
$app->delete('/api/user/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from users where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"user deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});




// student api
// add student
$app->post('/api/student/register/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $fname = $data['fname'];$lname = $data['lname']; $username = $data['username'];
    $email = $data['emailAddress']; $password = $data['password'];$roleId = $data['role_id'];
    $gender = $data['gender']; $parentId = $data['parent_id']; $sectionId = $data['section_id'];

    
    $sql = "insert into users(fname, lname, username, password, emailAddress, role_id, gender) values (:fname, :lname, :username, :password, :emailAddress, :roleId, :gender)";
    $sql1 = "insert into students(user_id, parent_id, section_id) values (:userid, :parentid, :sectionid)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['fname'=>$fname,'lname'=>$lname, 'username'=>$username, 'password'=>$password, 'emailAddress'=>$email, 'roleId'=>$roleId, 'gender'=>$gender]);
        $db = null;
        echo '{"message":"user added success"}';
        try{
            $query = "select id from users order BY id desc limit 1;";
            $db = new db();
            $db = $db->connect();
            $statement = $db->query($query);        
            $userId = $statement->fetch();
            $userId = $userId['id'];
            $db = null;
            echo '{"message":"userid fetched success"}';
            try{
                $db = new db();
                $db = $db->connect();
                $statement = $db->prepare($sql1);       
                $statement->execute(['userid'=>$userId,'parentid'=>$parentId, 'sectionid'=>$sectionId]);
                $db = null;
                echo '{"message":"student added success"}';
            }catch(PDOException $e){
            echo '{"error": {"text": "'.$e->getMessage().'"}}';
        }

        }catch(PDOException $e){
            echo '{"error": {"text": "'.$e->getMessage().'"}}';
        }

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get single student
$app->post('/api/student/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT* FROM students where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get students
$app->get('/api/student/', function(Request $request, Response $response){
    $sql = "SELECT* FROM students";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for student
$app->put('/api/student/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $username = $data["username"];
    $id = $data["id"];
    
    $sql = "update students set username = :username where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['username'=>$username, 'id'=>$id]);

        echo '{"message":"username updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete user queries
$app->delete('/api/student/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from students where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"user deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});








// teacher api
// add teacher
$app->post('/api/teacher/register/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $fname = $data['fname'];$lname = $data['lname']; $username = $data['username'];
    $email = $data['emailAddress']; $password = $data['password'];$roleId = $data['role_id'];
    $gender = $data['gender']; $sectionId = $data['section_id'];

    
    $sql = "insert into users(fname, lname, username, password, emailAddress, role_id, gender) values (:fname, :lname, :username, :password, :emailAddress, :roleId, :gender)";
    $sql1 = "insert into teachers(user_id, section_id) values (:userid, :sectionid)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['fname'=>$fname,'lname'=>$lname, 'username'=>$username, 'password'=>$password, 'emailAddress'=>$email, 'roleId'=>$roleId, 'gender'=>$gender]);
        $db = null;
        echo '{"message":"user added success"}';
        try{
            $query = "select id from users order BY id desc limit 1;";
            $db = new db();
            $db = $db->connect();
            $statement = $db->query($query);        
            $userId = $statement->fetch();
            $userId = $userId['id'];
            $db = null;
            echo '{"message":"userid fetched success"}';
            try{
                $db = new db();
                $db = $db->connect();
                $statement = $db->prepare($sql1);       
                $statement->execute(['userid'=>$userId, 'sectionid'=>$sectionId]);
                $db = null;
                echo '{"message":"student added success"}';
            }catch(PDOException $e){
            echo '{"error": {"text": "'.$e->getMessage().'"}}';
        }

        }catch(PDOException $e){
            echo '{"error": {"text": "'.$e->getMessage().'"}}';
        }

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get single teacher
$app->post('/api/teacher/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT* FROM teachers where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get teachers
$app->get('/api/teacher/', function(Request $request, Response $response){
    $sql = "SELECT* FROM teachers";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for teacher
$app->put('/api/teacher/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $username = $data["username"];
    $id = $data["id"];
    
    $sql = "update teachers set username = :username where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['username'=>$username, 'id'=>$id]);

        echo '{"message":"username updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete teacher queries
$app->delete('/api/teacher/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from teachers where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"user deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});









// parent api
// add parent
$app->post('/api/parent/register/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $fname = $data['fname'];$lname = $data['lname']; $username = $data['username'];
    $email = $data['emailAddress']; $password = $data['password'];$roleId = $data['role_id'];
    $gender = $data['gender']; $phoneNumber = $data['phone_number'];

    
    $sql = "insert into users(fname, lname, username, password, emailAddress, role_id, gender) values (:fname, :lname, :username, :password, :emailAddress, :roleId, :gender)";
    $sql1 = "insert into parents(user_id, phone_number) values (:userid, :phoneNumber)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['fname'=>$fname,'lname'=>$lname, 'username'=>$username, 'password'=>$password, 'emailAddress'=>$email, 'roleId'=>$roleId, 'gender'=>$gender]);
        $db = null;
        echo '{"message":"user added success"}';
        try{
            $query = "select id from users order BY id desc limit 1;";
            $db = new db();
            $db = $db->connect();
            $statement = $db->query($query);        
            $userId = $statement->fetch();
            $userId = $userId['id'];
            $db = null;
            echo '{"message":"userid fetched success"}';
            try{
                $db = new db();
                $db = $db->connect();
                $statement = $db->prepare($sql1);       
                $statement->execute(['userid'=>$userId,'parentNumber'=>$phoneNumber]);
                $db = null;
                echo '{"message":"student added success"}';
            }catch(PDOException $e){
            echo '{"error": {"text": "'.$e->getMessage().'"}}';
        }

        }catch(PDOException $e){
            echo '{"error": {"text": "'.$e->getMessage().'"}}';
        }

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get single parent
$app->post('/api/parent/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT* FROM parents where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});


// get parents
$app->get('/api/parent/', function(Request $request, Response $response){
    $sql = "SELECT* FROM parents";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for parent
$app->put('/api/parent/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $username = $data["username"];
    $id = $data["id"];
    
    $sql = "update parents set username = :username where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['username'=>$username, 'id'=>$id]);

        echo '{"message":"username updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete parent queries
$app->delete('/api/parent/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from parents where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"user deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});






// attendance api
// add multiple attendance
$app->post('/api/student/attendances/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $attendances = $data["attendances"];

    $sql = "insert into attendances(student_id, section_id) values";
    $count = 0;
    $arrlength = count($attendances);
    foreach($attendances as $attendance){
        $count = $count + 1;
        
        if($count < $arrlength){
            $sql = $sql ."(". $attendance['student_id'].', '.$attendance['section_id']. "),";
        }else{
            $sql = $sql ."(". $attendance['student_id'].', '.$attendance['section_id']. ")";
        }
    }

    echo $sql;
    $db = new db();
    $db = $db->connect();
    $statement = $db->prepare($sql);        
    $statement->execute();
    $db = null;
    echo '{"message":"success"}';
});

// add attendance
$app->post('/api/attendance/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $section_id = $data['section_id'];$student_id = $data['student_id']; $status = $data['status'];

    
    $sql = "insert into attendances(section_id, student_id, status) values (:section_id, :student_id, :status)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['section_id'=>$section_id,'student_id'=>$student_id, 'status'=>$status]);
        $db = null;
        echo '{"message":"attendance added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get attendances
$app->get('/api/attendance/', function(Request $request, Response $response){
    $sql = "SELECT* FROM attendances";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single attendance
$app->post('/api/attendance/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT* FROM attendances where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for attendance
$app->put('/api/attendance/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $status = $data["status"];
    $id = $data["id"];
    
    $sql = "update attendances set status = :status where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['status'=>$status, 'id'=>$id]);

        echo '{"message":"attendance updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete attendance queries
$app->delete('/api/attendance/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from attendances where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"attendance deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});





//// assessment api
// add assessment
$app->post('/api/assessment/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $subject_id = $data['subject_id'];$student_id = $data['student_id']; $assessment_type_id = $data['assessment_type_id']; $score = $data['score'];

    
    $sql = "insert into assessments(subject_id, student_id, assessment_type_id, score) values (:subject_id, :student_id, :assessment_type_id, :score)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['subject_id'=>$subject_id,'student_id'=>$student_id, 'assessment_type_id'=>$assessment_type_id, 'score'=>$score]);
        $db = null;
        echo '{"message":"assessment added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get assessments
$app->get('/api/assessment/', function(Request $request, Response $response){
    $sql = "SELECT* FROM assessments";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single assessment
$app->post('/api/assessment/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM assessments where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for assessment
$app->put('/api/assessment/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $score = $data["score"];
    $id = $data["id"];
    
    $sql = "update assessments set score = :score where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['score'=>$score, 'id'=>$id]);

        echo '{"message":"assessment updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete assessment queries
$app->delete('/api/assessment/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from assessments where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"assessment deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});




//// section api
// add section
$app->post('/api/section/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $section_name = $data['section_name'];

    
    $sql = "insert into sections(section_name) values (:section_name)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['section_name'=>$section_name]);
        $db = null;
        echo '{"message":"section added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get sections
$app->get('/api/section/', function(Request $request, Response $response){
    $sql = "SELECT* FROM sections";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single section
$app->post('/api/section/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM sections where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for section
$app->put('/api/section/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $section_name = $data["section_name"];
    $id = $data["id"];
    
    $sql = "update sections set section_name = :section_name where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['section_name'=>$section_name, 'id'=>$id]);

        echo '{"message":"section updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete section queries
$app->delete('/api/section/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from sections where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"section deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});




//// subject api
// add subject
$app->post('/api/subject/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $subject_name = $data['subject_name'];

    
    $sql = "insert into subjects(subject_name) values (:subject_name)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['subject_name'=>$subject_name]);
        $db = null;
        echo '{"message":"subject added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get subjects
$app->get('/api/subject/', function(Request $request, Response $response){
    $sql = "SELECT* FROM subjects";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single subject
$app->post('/api/subject/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM subjects where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for subject
$app->put('/api/subject/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $subject_name = $data["subject_name"];
    $id = $data["id"];
    
    $sql = "update subjects set subject_name = :subject_name where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['subject_name'=>$subject_name, 'id'=>$id]);

        echo '{"message":"subject updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete subject queries
$app->delete('/api/subject/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from subjects where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"subject deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});




//// Role api
// add role
$app->post('/api/role/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $name = $data['name'];

    
    $sql = "insert into roles(name) values (:name)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['name'=>$name]);
        $db = null;
        echo '{"message":"role added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get roles
$app->get('/api/role/', function(Request $request, Response $response){
    $sql = "SELECT* FROM roles";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single role
$app->post('/api/role/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM roles where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for role
$app->put('/api/role/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $name = $data["name"];
    $id = $data["id"];
    
    $sql = "update roles set name = :name where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['name'=>$name, 'id'=>$id]);

        echo '{"message":"role updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete role queries
$app->delete('/api/role/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from roles where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"role deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});




//// section subject api
// add sectionSubject
$app->post('/api/sectionSubject/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $subject_id = $data['subject_id']; $section_id = $data['section_id'];

    
    $sql = "insert into section_subject(subject_id, section_id) values (:subject_id, :section_id)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['subject_id'=>$subject_id, 'section_id'=>$section_id]);
        $db = null;
        echo '{"message":"sectionSubject added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get sectionSubjects
$app->get('/api/sectionSubject/', function(Request $request, Response $response){
    $sql = "SELECT * FROM section_subject";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single sectionSubject
$app->post('/api/sectionSubject/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM section_subject where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for sectionSubject
$app->put('/api/sectionSubject/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $subject_id = $data["subject_id"]; $section_id = $data["section_id"];
    $id = $data["id"];
    
    $sql = "update section_subject set subject_id = :subject_id, section_id = :section_id where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['subject_id'=>$subject_id, 'section_id'=>$section_id, 'id'=>$id]);

        echo '{"message":"sectionSubject updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete section sectionSubject
$app->delete('/api/sectionSubject/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from section_subject where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"sectionSubject deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});





//// assessment type api
// add assessmentType
$app->post('/api/assessmentType/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $name = $data['name']; $maximum_point = $data['maximum_point'];

    
    $sql = "insert into assessmentTypes(name, maximum_point) values (:name, :maximum_point)";

    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['name'=>$name, 'maximum_point'=>$maximum_point]);
        $db = null;
        echo '{"message":"assessmentType added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }

});

// get assessmentTypes
$app->get('/api/assessmentType/', function(Request $request, Response $response){
    $sql = "SELECT * FROM assessmentTypes";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// get single assessmentType
$app->post('/api/assessmentType/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM assessmentTypes where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);

    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
});

// put query for assessmentType
$app->put('/api/assessmentType/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $name = $data["name"]; $maximum_point = $data["maximum_point"];
    $id = $data["id"];
    
    $sql = "update assessmentTypes set name = :name, maximum_point = :maximum_point where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['name'=>$name, 'maximum_point' => $maximum_point, 'id'=>$id]);

        echo '{"message":"assessmentType updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
});

// delete assessmentType
$app->delete('/api/assessmentType/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from assessmentTypes where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"assessmentType deleted successfully"}';

    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
});




//// announcement api
// add announcement
$app->post('/api/announcement/add/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(),true);
    $title = $data['title'];$description = $data['description'];
    
    
    $sql = "insert into announcements(title, description) values (:title, :description)";
    
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);        
        $statement->execute(['title'=>$title, 'description'=>$description]);
        $db = null;
        echo '{"message":"announcement added successfully"}';
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }    
});
    
    // get assessments
    $app->get('/api/announcement/', function(Request $request, Response $response){
    $sql = "SELECT* FROM announcements";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
    });
    
    // get single announcement
    $app->post('/api/announcement/one/', function(Request $request, Response $response){
    $data = json_decode($request->getBody(), true);
    $id = $data['id'];
    $sql = "SELECT * FROM announcements where id = ".$id."";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->query($sql);
        $user = $statement->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    
    }catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}}';
    }
    });
    
    // put query for announcement
    $app->put('/api/announcement/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $title = $data["title"]; $description = $data["description"];
    $id = $data["id"];
    
    $sql = "update announcements set title = :title, description = :description where id = :id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['title'=>$title, 'description'=> 'description', 'id'=>$id]);
    
        echo '{"message":"announcement updated successfully"}';
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';        
    }
    });
    
    // delete announcement queries
    $app->delete('/api/announcement/', function(Request $request){
    $data = json_decode($request->getBody(), true);
    $id = $data["id"];
    $sql = "delete from announcements where id=:id";
    try{
        $db = new db();
        $db = $db->connect();
        $statement = $db->prepare($sql);
        $statement->execute(['id'=>$id]);
        echo '{"message":"announcement deleted successfully"}';
    
    }catch(PDOException $e){
        echo '{"error":{"text":"'.$e->getMessage().'"}}';
    }
    });
    