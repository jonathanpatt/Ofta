<?php
/**
 * Image manipulation
 *
 * PHP Version 5
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */

class FileNotFoundException extends Exception { }
class LargerThanSourceException extends Exception { }
class CouldNotSaveFileException extends Exception { }

/**
 * Class to manipulate an image and save the result
 *
 * Loads an image from a specified path, manipulates it, and saves
 * the result as a new file, in JPG, PNG or GIF format.
 *
 * @category Ofta
 * @package  Ofta
 * @author   Jonathan Patt <jonathanpatt@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license BSD License
 * @link     https://github.com/jonathanpatt/Ofta
 */
class ImageManipulator
{
    protected $image;
    
    /**
     * initialize new image from path
     *
     * @param string $imagePath path to image to load 
     *
     * @throws FileNotFoundException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    function __construct($imagePath)
    {
        try {
            // Load image file
            if (!$file = @file_get_contents($imagePath)) {
                throw new Exception('no file found at specified path.');
            }
            
            // Create image from loaded file
            if (!$this->image = @imagecreatefromstring($file)) {
                throw new Exception('specified file is of an invalid type.');
            }
        } catch (Exception $e) {
            throw new FileNotFoundException(
                'Image load error: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * current image width
     *
     * @return int
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function width()
    {
        return imagesx($this->image);
    }
    
    /**
     * current image height
     *
     * @return int
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function height()
    {
        return imagesy($this->image);
    }
    
    /**
     * crop image to specified dimensions
     *
     * @param int $cropWidth  the width in pixels to crop to
     * @param int $cropHeight the height in pixels to crop to
     *
     * @return void
     * 
     * @throws LargerThanSourceException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function crop($cropWidth, $cropHeight)
    {
        if ($cropWidth > $this->width() || $cropHeight > $this->height()) {
            throw new LargerThanSourceException(
                'Crop dimensions cannot be larger than source image.'
            );
        }
        
        $imageCrop = imagecreatetruecolor($cropWidth, $cropHeight);
        
        imagecopyresampled(
            $imageCrop,
            $this->image,
            0 - ($this->width() - $cropWidth) / 2,
            0 - ($this->height() - $cropHeight) / 2,
            0,
            0,
            $this->width(),
            $this->height(),
            $this->width(),
            $this->height()
        );
        
        $this->image = $imageCrop;
    }
    
    /**
     * resize image to specified dimensions
     *
     * @param int $newWidth  the width in pixels to resize to
     * @param int $newHeight the height in pixels to resize to
     *
     * @return void
     *
     * @throws LargerThanSourceException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function resize($newWidth, $newHeight)
    {
        if ($newWidth > $this->width() || $newHeight > $this->height()) {
            throw new LargerThanSourceException(
                'Resize dimensions cannot be larger than source image.'
            );
        }
        
        $imageResize = imagecreatetruecolor($newWidth, $newHeight);
        
        imagecopyresampled(
            $imageResize,
            $this->image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $this->width(),
            $this->height()
        );
        
        $this->image = $imageResize;
    }
    
    /**
     * crop image to fit and then resize to specified dimensions,
     * preserving the correct aspect ratio and keeping as much of
     * the original image as possible
     *
     * @param string $newWidth  the width in pixels of the final image
     * @param string $newHeight the height in pixels of the final image
     *
     * @return void
     *
     * @throws LargerThanSourceException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function cropToFitAndResize($newWidth, $newHeight)
    {
        if ($this->width() < $newWidth || $this->height() < $newHeight) {
            throw new LargerThanSourceException(
                'Final dimensions cannot be larger than source image.'
            );
        }
        
        // Get ratio of difference between original and target dimensions
        $widthRatio  = $newWidth / $this->width();
        $heightRatio = $newHeight / $this->height();
        
        // Get crop dimensions
        if ($widthRatio >= $heightRatio) { // crop vertically or square
            $cropWidth  = $this->width();
            $cropHeight = round($newHeight * ($this->width() / $newWidth));
        } else if ($heightRatio > $widthRatio) { // crop horizontally
            $cropWidth  = round($newWidth * ($this->height() / $newHeight));
            $cropHeight = $this->height();
        }
        
        // Crop image
        $this->crop($cropWidth, $cropHeight);
        
        // Resize image
        $this->resize($newWidth, $newHeight);
    }
    
    /**
     * save image as JPG
     *
     * @param string $path    path to new file, including filename
     * @param string $quality quality of JPG file, from 0 to 100
     *
     * @return void
     *
     * @throws CouldNotSaveFileException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function saveJPG($path, $quality = 90)
    {
        if (!@imagejpeg($this->image, $path, $quality)) {
            throw new CouldNotSaveFileException('Unable to save JPG file.');
        }
        
    }
    
    /**
     * alias for saveJPG()
     *
     * @param string $path    path to new file, including filename
     * @param string $quality quality of JPG file, from 0 to 100
     *
     * @return void
     *
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function saveJPEG($path, $quality = 90)
    {
        $this->saveJPG($path, $quality);
    }
    
    /**
     * save image as PNG
     *
     * @param string $path path to new file, including filename
     *
     * @return void
     *
     * @throws CouldNotSaveFileException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function savePNG($path)
    {
        if (!@imagepng($this->image, $path)) {
            throw new CouldNotSaveFileException('Unable to save PNG file.');
        }
    }
    
    /**
     * save image as GIF
     *
     * @param string $path path to new file, including filename
     *
     * @return void
     *
     * @throws CouldNotSaveFileException
     * @author Jonathan Patt <jonathanpatt@gmail.com>
     */
    public function saveGIF($path)
    {
        if (!@imagegif($this->image, $path)) {
            throw new CouldNotSaveFileException('Unable to save GIF file.');
        }
    }
}
?>