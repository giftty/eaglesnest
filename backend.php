    <?php
   // $input = file_get_contents('php://input');

// Decode JSON to PHP array
//$data = json_decode($input, true);
    $pdo='';
    try {
    $pdo = new PDO("sqlite: database.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Set error mode for better error handling
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            firstname TEXT NOT NULL,
            lastname TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            kingschat TEXT,
            address TEXT,
            business_name TEXT,
            business_address TEXT,
            score INTEGER DEFAULT 0
        )
    ";

    // Execute the SQL statement
    $pdo->exec($sql);

    if(isset($_POST["score"])) {
         $stmt = $pdo->query("SELECT * FROM users WHERE email=$_POST[email] AND business_name=$_POST[business_name]");
        // Fetch all results
        if($stmt->rowCount() > 0) {
            echo "Business registration already exist.";
            exit();
        }

        $stmt = $pdo->prepare("
        INSERT INTO users (
            firstname,
            lastname,
            email,
            kingschat,
            address,
            business_name,
            business_address,
            score
        ) VALUES (
            :firstname,
            :lastname,
            :email,
            :kingschat,
            :address,
            :business_name,
            :business_address,
            :score
        )
    ");

    // Example data to insert
    $data = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'email' => $_POST['email'],
        'kingschat' => $_POST['kingschat'],
        'address' => $_POST['address'],
        'business_name' => $_POST['business_name'],
        'business_address' => $_POST['business_address'],
        'score' => $_POST['score']
    ];

    // Execute the statement with the data
      if($stmt->execute($data)){
         echo 'registration success';
       }
    }

    if(isset($_POST['get_users'])) {
        $stmt = $pdo->query('SELECT * FROM users');
        // Fetch all results
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);

    }
   
    if(isset($_GET['delete'])) {
        
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        if( $stmt->execute([':id' => $_GET['delete'] ])){
            echo "User resgistration deleted sucessfully.";
        }else{
            echo $stmt->errorInfo();
        }
    }
    ?>