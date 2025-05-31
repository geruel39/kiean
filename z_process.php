<?php
include 'z_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$current_date = date("Y-m-d");
$current_time = date("H:i");
$current_time = date("H:i", strtotime($current_time . " +6 hours"));

if(isset($data['userLogIn'])){
    $username = $data['username'];
    $password = $data['password'];

    $checkSql = "SELECT * FROM accounts WHERE username = :user";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':user' => $username]);
    if($checkStmt->rowCount() < 1){
        echo json_encode(['result' => false, 'message' => 'User not found.']);
        exit;
    }
    $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if($checkResult['password'] != $password){
        echo json_encode(['result' => false, 'message' => 'Incorrect password.']);
    }else{
        session_start();

        $_SESSION['id'] = $checkResult['account_id'];
        $_SESSION['role'] = $checkResult['role'];
        echo json_encode(['result' => true, 'role' => $checkResult['role']]);
    }
}

if(isset($data['logout'])){
    session_start();
    session_unset();
    session_destroy();

    echo json_encode(['result' => true]);
}

if(isset($data['getBranch'])){
    $user = $data['user'];
    $location = 0;

    $sql = "SELECT * FROM accounts WHERE account_id = :user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user' => $user]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result['location'] == 0){
        $sql = "SELECT * FROM access WHERE account = :user LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user' => $user]);
        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() != 1){
            echo json_encode(['result' => false, 'message' => 'This account has no access to any branch.']);
        }

        $location = $result2['branch'];
    }else{
        $location = $result['location'];
        
        $stmt = $pdo->prepare("UPDATE accounts SET location = :loc WHERE account_id = :user");
        $stmt->execute([':loc' => $location, 'user' => $user]);
    }

    $sql = "SELECT * FROM branch WHERE branch_id = :branch";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':branch' => $location]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'branch' => $result]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Something went wrong..']);
    }
}

if(isset($data['getBranchList'])){
    $user = $data['user'];

    $sql = "SELECT access.*, branch.name AS name FROM access LEFT JOIN branch ON branch.branch_id=access.branch WHERE access.account=:user";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user' => $user]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'branch' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['swapBranch'])){
    $branch = $data['branch'];
    $user = $data['user'];

    $sql = "UPDATE accounts SET location = :branch WHERE account_id=:user";
    $stmt = $pdo->prepare($sql);

    if($stmt->execute([':branch' => $branch, ':user' => $user])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['addNewBranch'])){

    $name = $data['name'];
    $commission = $data['commission'];
    $location = $data['location'];

    $checkStmt = $pdo->prepare("SELECT * FROM branch WHERE name = :name");
    $checkStmt->execute([':name' => $name]);
    if($checkStmt->rowCount() > 0){
        echo json_encode(['result' => false, 'message' => 'Branch aleady exist.']);
        exit;
    }

    $sql = "INSERT INTO branch (name, commission, location) VALUES (:name, :com, :location)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([':name' => $name,':com' => $commission,':location' => $location])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Something went wrong.']);
    }
}

if(isset($data['displayBranches'])){

    $sql = "SELECT 
    branch.*, 
    COUNT(access.branch) AS access
    FROM 
        branch
    LEFT JOIN 
        access ON branch.branch_id = access.branch
    GROUP BY 
        branch.branch_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'branches' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }


}

if(isset($data['getBranchDetails'])){
    $id = $data['id'];

    $stmt = $pdo->prepare("SELECT * FROM branch WHERE branch_id=:i");
    $stmt->execute([':i' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['branch' => $result]);
}

if(isset($data['editBranchDetails'])){
    $id = $data['id'];

    $name = $data['name'];
    $commission = $data['commission'];
    $location = $data['location'];

    $sql = "UPDATE branch SET name=:n, commission=:c, location=:l WHERE branch_id=:i";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([':n' => $name, ':c' => $commission, ':l' => $location, ':i' => $id])){
        echo json_encode(true);
    }else{
        echo json_encode(false);
    }
}

if(isset($data['addNewRole'])){
    $role = $data['role'];

    $checkStmt = $pdo->prepare("SELECT * FROM roles WHERE role = :role");
    $checkStmt->execute(['role' => $role]);
    if($checkStmt->rowCount() > 0 ){
        echo json_encode(['result' => false, 'message' => 'Role already exist.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO roles (role) VALUES (:role)");

    if($stmt->execute([':role' => $role])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Something went wrong.']);
    }
}

if(isset($data['insertRoles'])){

    $stmt = $pdo->prepare("SELECT * FROM roles");
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['result' => true, 'roles' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['addNewAccount'])){

    $username = $data['username'];
    $password = $data['password'];
    $role = $data['role'];

    $checkSql = "SELECT * FROM accounts WHERE username = :user";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':user' => $username]);
    if($checkStmt->rowCount() > 0){
        echo json_encode(['result' => false, 'message' => 'Username is used.']);
        exit;
    }

    $sql = "INSERT INTO accounts (username, password, role, location) VALUES (:user, :pass, :role, 0)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([':user' => $username, ':pass' => $password, ':role' => $role])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Something went wrong.']);
    }
}

if(isset($data['displayAccounts'])){

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE role!='Admin'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'accounts' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['deleteAcc'])){
    $account = $data['account'];

    try {
        $sql = "DELETE FROM accounts WHERE account_id=:id";
        $stmt = $pdo->prepare($sql);
    
        if ($stmt->execute([':id' => $account])) {
            echo json_encode(['result' => true]);
        } else {
            echo json_encode(['result' => false]);
        }
    } catch (PDOException $e) {
        echo json_encode(['result' => false]);
    }
}

if(isset($data['selectAccount'])){

    $branch = $data['branch'];

    $sql = "SELECT DISTINCT accounts.*
    FROM accounts
    LEFT JOIN access 
        ON accounts.account_id = access.account
        AND access.branch = :branch
    WHERE 
    accounts.role != 'Admin' 
    AND (access.account IS NULL OR access.branch != :branch);
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':branch' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'accounts' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['selectRemoveAccount'])){
    $branch = $data['branch'];

    $sql = "SELECT accounts.* FROM accounts LEFT JOIN access ON accounts.account_id = access.account 
    WHERE access.branch = :branch AND accounts.role != 'Admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':branch' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'accounts' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['giveAccess'])){
    $account = $data['account'];
    $branch = $data['branch'];

    $sql = "INSERT INTO access (branch, account) VALUES (:branch, :acc)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([':branch' => $branch, ':acc' => $account])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['removeAccess'])){

    $account = $data['account'];
    $branch = $data['branch'];

    $sql = "DELETE FROM access WHERE account = :acc AND branch = :branch";
    $stmt = $pdo->prepare($sql);

    if($stmt->execute([':acc' => $account, ':branch' => $branch])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['addProgram'])){

    $program = $data['program'];
    
    $sql = "INSERT INTO programs (program) VALUES (:p)";
    $stmt = $pdo->prepare($sql);

    if($stmt->execute([':p' => $program])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['addProgramList'])){

    $program = $data['program'];
    $under = $data['under'];

    $sql = "INSERT INTO programs (program, under) VALUES (:p, :u)";
    $stmt = $pdo->prepare($sql);

    if($stmt->execute([':p' => $program, ':u' => $under])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['insertProgram'])){

    $stmt = $pdo->prepare("SELECT * FROM programs WHERE under='None'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'programs' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['displayPrograms'])){

    $stmt = $pdo->prepare("SELECT * FROM programs");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'programs' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['addTuteeInfo'])){

    $fname = $data['fname'] ?? '';
    $mname = $data['mname'] ?? '';
    $lname = $data['lname'] ?? '';
    $gender = $data['gender'] ?? '';
    $bday = $data['bday'] ?? '';
    $g_fname = $data['g_fname'] ?? '';
    $g_mname = $data['g_mname'] ?? '';
    $g_lname = $data['g_lname'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $address = $data['address'] ?? '';

    $branch = $data['branch'] ?? '';
    $status = "Active";

    if($fname){
        $sql = "SELECT * FROM std_info WHERE fname=:f AND lname=:l";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':f' => $fname, ':l' => $lname]);
        if($stmt->rowCount() > 0){
            echo json_encode(['result' => false, 'message' => "$fname $lname information is already exist. You can edit his/her information in Tutees Information page"]);
            exit;
        }
    }
    

    $sql = "INSERT INTO std_info (fname, mname, lname, gender, bday, g_fname, g_mname, g_lname, email, phone, address, branch, status)
    VALUES (:fname, :mname, :lname, :gender, :bday, :g_fname, :g_mname, :g_lname, :email, :phone, :address, :branch, :status)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':mname', $mname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':bday', $bday);
    $stmt->bindParam(':g_fname', $g_fname);
    $stmt->bindParam(':g_mname', $g_mname);
    $stmt->bindParam(':g_lname', $g_lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':branch', $branch);
    $stmt->bindParam(':status', $status);

    if($stmt->execute()){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }


}

if(isset($data['displayTuteesInfo'])){
    $branch = $data['branch'];
    $search = "%" . $data['search'] . "%";

    $sql = "SELECT * FROM std_info WHERE branch = :b AND status = 'Active' AND (fname LIKE :s OR mname LIKE :s OR lname LIKE :s)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':b' => $branch, ':s' => $search]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'infos' => $result ]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['displayTuteeListInfo_s'])){

    $search = "%" . $data['search'] . "%";
    $branch = "%" . $data['branch'] . "%";

    $sql = "SELECT std_info.*, branch.name FROM std_info LEFT JOIN branch ON std_info.branch=branch.branch_id
    WHERE (std_info.fname LIKE :s OR std_info.mname LIKE :s OR std_info.lname LIKE :s) 
    AND std_info.status='Active' AND std_info.branch LIKE :b LIMIT 300";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':s' => $search, ':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'infos' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['enrollTutee'])){
    
    $id = $data['id'];
    $program = $data['program'];
    $rates = $data['rates'];
    $date = $data['year'] . "-" . $data['month'] . "-" . "01";
    $branch = $data['branch'];

    if($id){
        $sql = "SELECT * FROM enrolled WHERE std_id = :s AND date=:d";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':s' => $id, ':d' => $date]);
        
        if($stmt->rowCount() > 0){
            echo json_encode(['result' => false, 'message' => 'Student is already has enrolled record in this date']);
            exit;
        }
    }

    $sql = "INSERT INTO enrolled (std_id, program, rates, date, branch) 
    VALUES (:s, :p, :r, :d, :b)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([
        ':s' => $id,
        ':p' => $program,
        ':r' => $rates,
        ':d' => $date,
        ':b' => $branch
    ])){

        $sql = "SELECT enrolled_id FROM enrolled WHERE std_id=:s AND program=:p AND rates=:r AND date=:d AND branch=:b";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':s' => $id,':p' => $program,':r' => $rates,':d' => $date,':b' => $branch]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $insert = "INSERT INTO payments (enroll_id, amount, date) VALUES (:e , 0, :d)";
        $inst = $pdo->prepare($insert);
        $inst->execute([':e' => $result['enrolled_id'],':d' => $date]);


        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Something went wrong']);
    }


}

if(isset($data['displayTutees'])){

    $branch = $data['branch'];
    $search = "%" . $data['search'] . "%";
    $program = "%" . $data['program'] . "%";
    $year = $data['year'];
    $month = $data['month'];

    $sql = "SELECT enrolled.*, std_info.fname, std_info.mname, std_info.lname FROM programs 
    RIGHT JOIN enrolled ON enrolled.program=programs.program OR enrolled.program=programs.under
    LEFT JOIN std_info ON std_info.std_id=enrolled.std_id
    WHERE (programs.program LIKE :p OR programs.under LIKE :p)
    AND (std_info.fname LIKE :s OR std_info.mname LIKE :s OR std_info.lname LIKE :s) 
    AND MONTH(enrolled.date)=:m AND YEAR(enrolled.date)=:y 
    AND enrolled.branch = :b
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':p' => $program, ':s' => $search, ':m' => $month, ':y' => $year, ':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'tutees' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['displayTuteeList_s'])){
    $year = $data['year'];
    $month = $data['month'];

    $sql = "SELECT std_info.fname, std_info.mname, std_info.lname, enrolled.rates , enrolled.program, SUM(payments.amount) AS paid
    FROM payments LEFT JOIN enrolled ON payments.enroll_id=enrolled.enrolled_id
    LEFT JOIN std_info ON enrolled.std_id=std_info.std_id
    WHERE
    YEAR(enrolled.date)=:y AND MONTH(enrolled.date)=:m 
    GROUP BY payments.enroll_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':y' => $year, ':m' => $month]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'tutees' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['unenrollTutee'])){

    $id = $data['id'];

    if(true){
        $sql = "SELECT * FROM payments WHERE enroll_id=:i AND amount > 0";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':i' => $id]);
        if($stmt->rowCount() != 0){
            echo json_encode(['result' => false]);
            exit();
        }else{
            $stmt =$pdo->prepare("DELETE FROM payments WHERE enroll_id = :i");
            $stmt->execute([':i' => $id]);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM enrolled WHERE enrolled_id=:id");
    
    if($stmt->execute([':id' => $id])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['addPayment'])){
    
    $id = $data['id'];
    $amount = $data['amount'];

    $sql = "INSERT INTO payments (enroll_id, amount, date) VALUES (:i, :a, :d)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([':i' => $id, ':a' => $amount, ':d' => $current_date])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['getTuteeInfoDetails'])){
    $tutee = $data['tutee'];

    $stmt = $pdo->prepare("SELECT * FROM std_info WHERE std_id=:t");
    $stmt->execute([':t' => $tutee]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['tutee' => $result]);
}

if(isset($data['editTuteeInfo'])){

    $id = $data['id'];

    $fname = $data['fname'];
    $mname = $data['mname'];
    $lname = $data['lname'];
    $gender = $data['gender'];
    $bday = $data['bday'];
    $gfname = $data['gfname'];
    $gmname = $data['gmname'];
    $glname = $data['glname'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];

    $sql = "UPDATE std_info 
    SET fname=:fn, mname=:mn, lname=:ln, gender=:g, bday=:b, g_fname=:gfn, g_mname=:gmn, g_lname=:gln, email=:e, phone=:p, address=:a WHERE std_id=:i";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([
        ':fn' => $fname,
        ':mn' => $mname,
        ':ln' => $lname,
        ':g' => $gender,
        ':b' => $bday,
        ':gfn' => $gfname,
        ':gmn' => $gmname,
        ':gln' => $glname,
        ':e' => $email,
        ':p' => $phone,
        ':a' => $address,
        ':i' => $id,
    ])){
        echo json_encode(true);
    }


}

if(isset($data['displayPaymentRecords'])){

    $branch = $data['branch'];
    $search = "%" . $data['search'] . "%";
    $year = $data['year'];
    $month = $data['month'];

    $sql = "SELECT enrolled.enrolled_id AS id ,std_info.fname, std_info.mname, std_info.lname, enrolled.rates, 
    SUM(payments.amount) AS paid, (enrolled.rates - SUM(payments.amount)) AS status 
    FROM payments LEFT JOIN enrolled ON payments.enroll_id=enrolled.enrolled_id
    LEFT JOIN std_info ON enrolled.std_id=std_info.std_id
    WHERE (std_info.fname LIKE :s OR std_info.mname LIKE :s OR std_info.lname LIKE :s) 
    AND YEAR(enrolled.date)=:y AND MONTH(enrolled.date)=:m 
    AND enrolled.branch=:b GROUP BY payments.enroll_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':s' => $search, ':y' => $year, ':m' => $month, ':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'records' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['displayPaymentHistory'])){
    $branch = $data['branch'];

    $sql = "SELECT payments.*, enrolled.date AS tp, CONCAT(std_info.fname, ' ', std_info.mname, ' ', std_info.lname) AS tutee
    FROM payments LEFT JOIN enrolled ON payments.enroll_id=enrolled.enrolled_id LEFT JOIN std_info ON enrolled.std_id=std_info.std_id 
    WHERE payments.via='Manual' AND payments.amount!=0 AND enrolled.branch = :b ORDER BY payments.payment_id DESC LIMIT 500"; 

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'records' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['deletePayment'])){
    $id = $data['id'];

    $stmt = $pdo->prepare("DELETE FROM payments WHERE payment_id=:id");
    if($stmt->execute(['id' => $id])){
        echo json_encode(true);
    }else{
        echo json_encode(false);
    }
}

if(isset($data['addNewExpensesType'])){
    $type = $data['type'];
    $branch = $data['branch'];

    if(true){
        $check = $pdo->prepare("SELECT * FROM expenses_t WHERE type=:t AND branch=:b");
        $check->execute([':t' => $type, ':b' => $branch]);
        if($check->rowCount() > 0){
            echo json_encode(['result' => false, 'message' => 'Expenses type already exist']);
            exit();
        }
    }

    $sql = "INSERT INTO expenses_t (type, branch) VALUES (:t, :b)";
    $stmt = $pdo->prepare($sql);

    if($stmt->execute([':t' => $type, ':b' => $branch])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Something went wrong.']);
    }
}

if(isset($data['insertTypes'])){
    $branch = $data['branch'];

    $sql = "SELECT * FROM expenses_t WHERE branch=:b ORDER BY type";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'types' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['addExpenses'])){
    $type = $data['type'];
    $amount = $data['amount'];
    $date = $data['date'];
    $branch = $data['branch'];

    if(true){
        $sql = "SELECT * FROM expenses WHERE type=:t AND date=:d AND branch=:b";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':t' => $type, ':d' => $date, ':b' => $branch]);
        if($stmt->rowCount() > 0){
            $sql = "UPDATE expenses SET amount = amount + :a WHERE type=:t AND date=:d AND branch=:b";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute([':a' => $amount ,':t' => $type, ':d' => $date, ':b' => $branch])){
                echo json_encode(['result' => true]);
            }else{
                echo json_encode(['result' => false]);
            }
            exit();
        }
    }

    $sql = "INSERT INTO expenses (type, amount, date, branch) VALUES (:t, :a, :d, :b)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([':t' => $type, ':a' => $amount ,':d' => $date, ':b' => $branch])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['deleteExpenses'])){
    $id = $data['id'];

    $stmt = $pdo->prepare("DELETE FROM expenses WHERE expenses_id=:id");
    if($stmt->execute([':id' => $id])){
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['displayOverview'])){
    $branch = $data['branch'];
    $year = $data['year'];
    $month = $data['month'];

    $payment = $pdo->prepare("SELECT SUM(payments.amount) AS total_pay FROM payments LEFT JOIN enrolled ON payments.enroll_id=enrolled.enrolled_id WHERE YEAR(enrolled.date)=:y AND MONTH(enrolled.date)=:m AND enrolled.branch=:b");
    $payment->execute([':y' => $year, ':m' => $month, ':b' => $branch]);
    $pay = $payment->fetch(PDO::FETCH_ASSOC);

    $expenses = $pdo->prepare("SELECT SUM(amount) AS total_exp FROM expenses WHERE YEAR(date)=:y AND MONTH(date)=:m AND branch=:b");
    $expenses->execute([':y' => $year, ':m' => $month, ':b' => $branch]);
    $exp = $expenses->fetch(PDO::FETCH_ASSOC);

    $supply = $pdo->prepare("SELECT SUM(cost) AS total_sup FROM inv_actions WHERE YEAR(date)=:y AND MONTH(date)=:m AND action='D' AND branch=:b");
    $supply->execute([':y' => $year, ':m' => $month, ':b' => $branch]);
    $sup = $supply->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['payments' => $pay['total_pay'], 'expenses' => $exp['total_exp'], 'supply' => $sup['total_sup']]);

}

if(isset($data['displayExpenses'])){
    $year = $data['year'];
    $month = $data['month'];
    $branch = $data['branch'];

    $sql = "SELECT * FROM expenses WHERE YEAR(date)=:y AND MONTH(date)=:m AND branch=:b ORDER BY type";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':y' => $year, ':m' => $month, ':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'expenses' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['displaySupplyUsed'])){
    $branch = $data['branch'];
    $year = $data['year'];
    $month = $data['month'];

    $sql = "SELECT items.item AS name, SUM(inv_actions.quantity) AS tq, SUM(inv_actions.cost) AS tc
    FROM inv_actions LEFT JOIN items ON items.item_id=inv_actions.item 
    WHERE YEAR(inv_actions.date)=:y AND MONTH(inv_actions.date)=:m 
    AND inv_actions.action='D' AND inv_actions.branch = :b GROUP BY inv_actions.item";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':y' => $year, ':m' => $month, ':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'used' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }

}

if(isset($data['displayOverviewTotal'])){
    $year = $data['year'];
    $month = $data['month'];

    $payments = $pdo->prepare("SELECT SUM(payments.amount) AS payments FROM payments LEFT JOIN enrolled ON enrolled.enrolled_id=payments.enroll_id WHERE YEAR(enrolled.date)=:y AND MONTH(enrolled.date)=:m");
    $payments->execute([':y' => $year, ':m' => $month]);
    $total_payments = $payments->fetch(PDO::FETCH_ASSOC);

    $expenses = $pdo->prepare("SELECT SUM(amount) AS expenses FROM expenses WHERE YEAR(date)=:y AND MONTH(date)=:m");
    $expenses->execute([':y' => $year, ':m' => $month]);
    $total_expenses = $expenses->fetch(PDO::FETCH_ASSOC);

    $supplies = $pdo->prepare("SELECT SUM(cost) AS supply FROM inv_actions WHERE YEAR(date)=:y AND MONTH(date)=:m AND action='D'");
    $supplies->execute([':y' => $year, ':m' => $month]);
    $total_supply = $supplies->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['payments' => $total_payments['payments'], 'expenses' => $total_expenses['expenses'], 'supply' => $total_supply['supply']]);

}

if(isset($data['displayFinancialRecords'])){
    $year = $data['year'];
    $month = $data['month'];

    $sql = "SELECT 
        b.*,
        COALESCE(p.sales, 0) AS sales,
        COALESCE(e.expenses, 0) AS expenses,
        COALESCE(i.supply, 0) AS supply,
        COALESCE(p.sales, 0) - (COALESCE(e.expenses, 0) + COALESCE(i.supply, 0)) AS income,
        (COALESCE(p.sales, 0) * COALESCE(b.commission, 0)) / 100 AS commission
    FROM branch b
    LEFT JOIN (
        SELECT enrolled.branch, SUM(payments.amount) AS sales
        FROM enrolled
        JOIN payments ON payments.enroll_id = enrolled.enrolled_id
        WHERE YEAR(enrolled.date)=:y AND MONTH(enrolled.date)=:m
        GROUP BY enrolled.branch
    ) p ON p.branch = b.branch_id
    LEFT JOIN (
        SELECT branch, SUM(amount) AS expenses
        FROM expenses
        WHERE YEAR(expenses.date)=:y AND MONTH(expenses.date)=:m
        GROUP BY branch
    ) e ON e.branch = b.branch_id
    LEFT JOIN (
        SELECT branch, SUM(cost) AS supply
        FROM inv_actions
        WHERE YEAR(inv_actions.date)=:y AND MONTH(inv_actions.date)=:m AND action='D'
        GROUP BY branch
    ) i ON i.branch = b.branch_id
    ORDER BY b.name
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':y' => $year, ':m' => $month]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0) {
        echo json_encode(['result' => true, 'branches' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($_POST['newItem'])){
    $name = $_POST['name'] ?? null;
    $cost = $_POST['cost'] ?? null;

    if (!$name || !$cost) {
        echo json_encode(['result' => false, 'message' => 'Item name or cost is missing.']);
        exit;
    }

    if(true){
        $sql = "SELECT * FROM items WHERE item=:n";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':n' => $name]);
        if($stmt->rowCount() > 0){
            echo json_encode(['result' => false, 'message' => 'Item name already exist.']);
            exit;
        }
    }

    if(isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileTmpName = $file['tmp_name'];
        $fileName = $file['name'];
        $fileError = $file['error'];
        $fileSize = $file['size'];

        if ($fileError === 0) {
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid('', true) . '.' . $fileExt;

            $uploadDir = 'images/inventory/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpName, $uploadDir . $uniqueFileName)) {
                $sql = "INSERT INTO items (item, cost, image) VALUES (:name, :cost, :image)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':cost' => $cost,
                    ':image' => $uniqueFileName
                ]);

                echo json_encode(['result' => true, 'message' => 'Item uploaded successfully.']);
            } else {
                echo json_encode(['result' => false, 'message' => 'File upload failed.']);
            }
        } else {
            echo json_encode(['result' => false, 'message' => 'Error with file upload.']);
        }
    } else {
        echo json_encode(['result' => false, 'message' => 'No file uploaded.']);
    }
}

if(isset($data['displayItems'])){

    $search = "%" . $data['search'] . "%";

    $sql = "SELECT * FROM items WHERE item LIKE :s";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':s' => $search]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'items' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($_POST['editItem'])){

    $id = $_POST['id'];
    $name = $_POST['name'] ?? null;
    $cost = $_POST['cost'] ?? null;

    if (!$name || !$cost) {
        echo json_encode(['result' => false, 'message' => 'Item name or cost is missing.']);
        exit;
    }

    if(isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileTmpName = $file['tmp_name'];
        $fileName = $file['name'];
        $fileError = $file['error'];
        $fileSize = $file['size'];

        $currentImage = null;

        if(true){
            $stmt = $pdo->prepare("SELECT * FROM items WHERE item=:i");
            $stmt->execute(['i' => $name]);
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentImage = $r['image'];
        }

        if ($fileError === 0) {
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid('', true) . '.' . $fileExt;

            $uploadDir = 'images/inventory/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($fileTmpName, $uploadDir . $uniqueFileName)) {
                $sql = "UPDATE items SET item=:name, cost=:cost, image=:image WHERE item_id=:i";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':cost' => $cost,
                    ':image' => $uniqueFileName,
                    ':i' => $id
                ]);

                unlink("images/inventory/$currentImage");

                echo json_encode(['result' => true, 'message' => 'Item edited successfully.']);
                exit;
            } else {
                echo json_encode(['result' => false, 'message' => 'File upload failed.']);
                exit;
            }
        } else {
            echo json_encode(['result' => false, 'message' => 'Error with file upload.']);
            exit;
        }
    }

    $sql = "UPDATE items SET item=:n, cost=:c WHERE item_id=:i";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':n' => $name,
        ':c' => $cost,
        ':i' => $id
    ]);

    echo json_encode(['result' => true, 'message' => 'Item edited successfully.']);
}

if(isset($data['displayItems_q'])){

    $search = "%" . $data['search'] . "%";
    $branch = $data['branch'];

    $sql = "SELECT items.*, inventory.quantity
    FROM items
    LEFT JOIN inventory ON items.item_id = inventory.item
        AND (inventory.branch = :b OR :b IS NULL)
    WHERE items.item LIKE :s";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':s' => $search, ':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'items' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

function inventoryActions($action, $item, $quantity, $branch){
    global $pdo;
    global $current_date;
    global $current_time;

    $getCost = $pdo->prepare("SELECT cost FROM items WHERE item_id=:i");
    $getCost->execute([':i' => $item]);
    $result = $getCost->fetch(PDO::FETCH_ASSOC);
    $cost = $result['cost'] * $quantity;
    
    $sql = "INSERT INTO inv_actions (action, item, quantity, cost, date, time, branch) 
    VALUES (:a, :i, :q, :c, :d, :t, :b)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':a' => $action,
                    ':i' => $item, 
                    ':q' => $quantity, 
                    ':c' => $cost, 
                    ':d' => $current_date, 
                    ':t' => $current_time, 
                    ':b' => $branch]);
}

if(isset($data['addQuantity'])){
    $id = $data['id'];
    $quantity = $data['quantity'];
    $branch = $data['branch'];

    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE item=:i");
    $stmt->execute([':i' => $id]);
    if($stmt->rowCount() > 0){
        $sql = "UPDATE inventory SET quantity = quantity + :q WHERE item=:i AND branch=:b";
        $stmt = $pdo->prepare($sql);
        
        if($stmt->execute([':q' => $quantity, ':i' => $id, ':b' => $branch])){
            inventoryActions("A", $id, $quantity, $branch);
            echo json_encode(['result' => true]);
        }else{
            echo json_encode(['result' => false, 'message' => 'Something went wrong.']);
        }

    }else{

        $sql = "INSERT INTO inventory (item, quantity, branch) 
        VALUES (:i, :q, :b)";
        $stmt = $pdo->prepare($sql);
        
        if($stmt->execute([':i' => $id, ':q' => $quantity, ':b' => $branch ])){
            inventoryActions("A", $id, $quantity, $branch);
            echo json_encode(['result' => true]);
        }else{
            echo json_encode(['result' => false, 'message' => 'Something went wrong.']);
        }
    }


}

if(isset($data['dedQuantity'])){
    $id = $data['id'];
    $quantity = $data['quantity'];
    $branch = $data['branch'];

    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE item=:i");
    $stmt->execute([':i' => $id]);
    if($stmt->rowCount() == 0){
        echo json_encode(['result' => false, 'message' => 'Cannot deduct an item with a quantity of 0.']);
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE item=:i AND branch=:b AND quantity <= :q ");
    $stmt->execute([':i' => $id, ':b' => $branch, ':q' => $quantity]);
    if($stmt->rowCount() > 0){
        echo json_encode(['result' => false, 'message' => "Cannot deduct an amount greater than the item's quantity."]);
        exit();
    }

    $sql = "UPDATE inventory SET quantity = quantity - :q WHERE item=:i AND branch=:b";
    $stmt = $pdo->prepare($sql);

    if($stmt->execute([':q' => $quantity, ':i' => $id, ':b' => $branch])){
        inventoryActions("D", $id, $quantity, $branch);
        echo json_encode(['result' => true]);
    }else{
        echo json_encode(['result' => false, 'message' => 'Someting went wrong.']);
    }

}

if(isset($data['displayActions'])){
    $branch = $data['branch'];

    $sql = "SELECT inv_actions.*, items.item AS name 
    FROM inv_actions LEFT JOIN items ON inv_actions.item=items.item_id
    WHERE branch =:b ORDER BY action_id DESC LIMIT 100";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':b' => $branch]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0){
        echo json_encode(['result' => true, 'actions' => $result]);
    }else{
        echo json_encode(['result' => false]);
    }
}

if(isset($data['undoAction'])){
    $branch = $data['branch'];

    $getLatest = $pdo->prepare("SELECT * FROM inv_actions WHERE action_id = (SELECT MAX(action_id) FROM inv_actions WHERE branch=:b)");
    $getLatest->execute([':b' => $branch]);
    $result = $getLatest->fetch(PDO::FETCH_ASSOC);

    if($getLatest->rowCount() > 0){
        if($result['action'] == "A"){
            $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity - :q WHERE item=:i AND branch=:b");
            $stmt->execute([':q' => $result['quantity'], ':i' => $result['item'], ':b' => $result['branch']]);
        }else{
            $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity + :q WHERE item=:i AND branch=:b");
            $stmt->execute([':q' => $result['quantity'], ':i' => $result['item'], ':b' => $result['branch']]);
        }
    }else{
        echo json_encode(false);
        exit;
    }

    
    $sql = "DELETE FROM inv_actions WHERE action_id = (SELECT  MAX(action_id) FROM inv_actions WHERE branch=:b)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([':b' => $branch])){
        echo json_encode(true);
    }else{
        echo json_encode(false);
    }
}


?>