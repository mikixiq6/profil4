<?php
$jsonFilePath = 'profile.json';
$profile = [];

if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    $profile = json_decode($jsonData, true) ?? [];
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_interest"])) {
    $newInterest = trim($_POST["new_interest"]);

    if (!empty($newInterest)) {
        if (!isset($profile['interests'])) {
            $profile['interests'] = [];
        }

        $lowerNewInterest = strtolower($newInterest);
        $existingInterestsLower = array_map('strtolower', $profile['interests']);

        if (!in_array($lowerNewInterest, $existingInterestsLower)) {
            $profile['interests'][] = $newInterest;
            if (file_put_contents($jsonFilePath, json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $message = "ZĂˇjem byl ĂşspÄ›ĹˇnÄ› pĹ™idĂˇn.";
                $messageType = "success";
            }
        } else {
            $message = "Tento zĂˇjem uĹľ existuje.";
            $messageType = "error";
        }
    } else {
        $message = "Pole nesmĂ­ bĂ˝t prĂˇzdnĂ©.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Profile - <?php echo htmlspecialchars($profile['name'] ?? 'Unknown'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; max-width: 600px; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        ul { padding-left: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        form { margin-top: 20px; margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; }
        .bubble-container { display: flex; flex-wrap: wrap; gap: 10px; padding: 0; margin-top: 10px; }
        .bubble { background-color: #007BFF; color: white; padding: 6px 14px; border-radius: 20px; font-size: 0.9em; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($profile['name'] ?? 'N/A'); ?></h1>
    
    <?php if (!empty($message)): ?>
        <p class="<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="new_interest" required>
        <button type="submit">PĹ™idat zĂˇjem</button>
    </form>

    <?php if (!empty($profile['skills'])): ?>
        <h2>Dovednosti (Skills)</h2>
        <ul>
            <?php foreach ($profile['skills'] as $skill): ?>
                <li><?php echo htmlspecialchars($skill); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($profile['projects'])): ?>
        <h2>Projekty (Projects)</h2>
        <ul>
            <?php foreach ($profile['projects'] as $project): ?>
                <li><?php echo htmlspecialchars($project); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($profile['interests'])): ?>
        <h2>ZĂˇjmy (Interests)</h2>
        <div class="bubble-container">
            <?php foreach ($profile['interests'] as $interest): ?>
                <span class="bubble"><?php echo htmlspecialchars($interest); ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
