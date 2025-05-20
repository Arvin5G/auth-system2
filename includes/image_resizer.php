<?php
function resizeImage($filePath, $maxWidth, $maxHeight) {
    // Suppress GD warnings (we'll handle errors properly)
    $errorLevel = error_reporting();
    error_reporting($errorLevel & ~E_WARNING);
    
    try {
        // Get image info
        list($origWidth, $origHeight, $type) = @getimagesize($filePath);
        if (!$origWidth || !$origHeight) {
            throw new Exception("Invalid image dimensions");
        }

        // Create image resource
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($filePath);
                // Preserve transparency
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($filePath);
                break;
            default:
                throw new Exception("Unsupported image type");
        }

        if (!$image) {
            throw new Exception("Failed to create image resource");
        }

        // Calculate new dimensions
        $ratio = $origWidth / $origHeight;
        if ($maxWidth / $maxHeight > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $ratio;
        }

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG/GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        // Resize image
        if (!imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight)) {
            throw new Exception("Failed to resize image");
        }

        // Save image
        $success = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($newImage, $filePath, 90);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($newImage, $filePath, 9);
                break;
            case IMAGETYPE_GIF:
                $success = imagegif($newImage, $filePath);
                break;
        }

        // Clean up
        imagedestroy($image);
        imagedestroy($newImage);
        
        if (!$success) {
            throw new Exception("Failed to save image");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Image resize error: " . $e->getMessage());
        return false;
    } finally {
        // Restore error reporting
        error_reporting($errorLevel);
    }
}