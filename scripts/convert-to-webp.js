import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { promisify } from 'util';

const readdir = promisify(fs.readdir);
const stat = promisify(fs.stat);

const IMAGES_DIR = './public/images';
const QUALITY = 90; // High quality for luxury brand

async function getAllFiles(dirPath, arrayOfFiles) {
  const files = await readdir(dirPath);
  arrayOfFiles = arrayOfFiles || [];

  for (const file of files) {
    const filePath = path.join(dirPath, file);
    if ((await stat(filePath)).isDirectory()) {
      if (file !== 'SVG') { // Skip SVG folder
        arrayOfFiles = await getAllFiles(filePath, arrayOfFiles);
      }
    } else {
      arrayOfFiles.push(filePath);
    }
  }

  return arrayOfFiles;
}

async function convertToWebp() {
  console.log('🚀 Iniciando conversión a WebP...');
  
  const files = await getAllFiles(IMAGES_DIR);
  const targetExtensions = ['.png', '.jpg', '.jpeg'];
  
  let count = 0;

  for (const file of files) {
    const ext = path.extname(file).toLowerCase();
    if (targetExtensions.includes(ext)) {
      const outputName = file.replace(ext, '.webp');
      
      try {
        await sharp(file)
          .webp({ quality: QUALITY, effort: 6 })
          .toFile(outputName);
        
        console.log(`✅ Convertido: ${file} -> ${outputName}`);
        count++;
      } catch (err) {
        console.error(`❌ Error convirtiendo ${file}:`, err);
      }
    }
  }

  console.log(`\n🎉 Finalizado. Se convirtieron ${count} imágenes.`);
}

convertToWebp();
