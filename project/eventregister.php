<?php
include("projectdb.php");

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $registrationNo = $_POST['registrationNo'] ?? '';
    $email = $_POST['email'] ?? '';
    $course = $_POST['course'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO registrations (name, registration_no, email, course, phone) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssss", $name, $registrationNo, $email, $course, $phone);
        if ($stmt->execute()) {
            $successMessage = "✅ Form submitted successfully!";
        } else {
            $successMessage = "❌ Error submitting form: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $successMessage = "❌ Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hackathon Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">

  <div class="bg-white bg-opacity-10 backdrop-blur-md px-8 py-16 rounded-3xl shadow-lg w-full max-w-lg text-white min-h-[650px]">
    <h2 class="text-3xl font-bold text-center mb-8">📝 Hackathon Registration</h2>

    <!-- Success Message -->
    <?php if (!empty($successMessage)): ?>
      <div class="mb-6 p-4 bg-green-500 bg-opacity-90 rounded text-white text-center font-semibold">
        <?php echo $successMessage; ?>
      </div>
    <?php endif; ?>

    <form class="space-y-6" action="register.php" method="POST">
       
      <!-- Student Name -->
      <input type="text" name="name" placeholder="Full Name" required
        class="w-full px-6 py-4 text-lg rounded-lg bg-black bg-opacity-20 text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-white" />

      <!-- Registration Number -->
      <input type="text" name="registrationNo" placeholder="Registration Number" required
        class="w-full px-6 py-4 text-lg rounded-lg bg-black bg-opacity-20 text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-white" />
          
      <!-- Email -->
      <input type="email" name="email" placeholder="Email" required
        class="w-full px-6 py-4 text-lg rounded-lg bg-black bg-opacity-20 text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-white" />
      
      <!-- Course Dropdown -->
      <select name="course" required
        class="w-full px-6 py-4 text-lg rounded-lg bg-black bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-white text-white">
        <option value="" disabled selected>Select Course</option>
        <option value="CSE">CSE</option>
        <option value="ECE">ECE</option>
        <option value="MECH">MECH</option>
        <option value="MBA">MBA</option>
      </select>

      <!-- Phone Number -->
      <input type="tel" name="phone" placeholder="Phone Number" required pattern="[0-9]{10}"
        class="w-full px-6 py-4 text-lg rounded-lg bg-black bg-opacity-20 text-white placeholder-white focus:outline-none focus:ring-2 focus:ring-white" />

      <!-- Submit Button--> 
      <button type="submit"
        class="w-full bg-white text-indigo-700 font-bold text-lg py-3 rounded-lg hover:bg-gray-100 transition">
        Submit Registration
      </button>
      
    </form>
  </div>

</body>
</html>
