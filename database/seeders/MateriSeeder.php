<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Modul;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moduls = Modul::whereHas('kursus', function($q) {
            $q->where('judul', 'Web Development');
        })->orderBy('urutan')->get();

        if ($moduls->isEmpty()) {
            $this->command->error('Modul Web Development tidak ditemukan. Jalankan ModulSeeder terlebih dahulu.');
            return;
        }

        $materiData = [];

        // Modul 1: Pengenalan Web Development
        $materiData[] = [
            'modul_id' => $moduls[0]->id,
            'judul' => 'Sejarah dan Evolusi Web',
            'deskripsi' => 'Memahami perkembangan web dari Web 1.0 hingga Web 3.0',
            'konten' => '<h1>Sejarah dan Evolusi Web</h1>
<p>World Wide Web (WWW) pertama kali diciptakan oleh <strong>Tim Berners-Lee</strong> pada tahun 1989 di CERN, Swiss. Sejak saat itu, web telah mengalami evolusi yang luar biasa.</p>

<h2>Era Web 1.0 (1991-2004)</h2>
<p>Web 1.0 dikenal sebagai <em>"Read-Only Web"</em>. Karakteristik utamanya:</p>
<ul>
<li>Website statis dengan konten yang jarang diupdate</li>
<li>Tidak ada interaksi pengguna</li>
<li>Informasi disajikan satu arah</li>
<li>Contoh: website perusahaan sederhana, katalog online</li>
</ul>

<h2>Era Web 2.0 (2004-sekarang)</h2>
<p>Web 2.0 atau <em>"Social Web"</em> membawa perubahan besar:</p>
<ul>
<li>Konten dinamis dan interaktif</li>
<li>User-generated content</li>
<li>Social media dan kolaborasi</li>
<li>Rich user experience dengan AJAX</li>
<li>Contoh: Facebook, YouTube, Wikipedia, Twitter</li>
</ul>

<h2>Era Web 3.0 (Emerging)</h2>
<p>Web 3.0 atau <em>"Semantic Web"</em> sedang berkembang dengan fitur:</p>
<ul>
<li>Decentralization dengan blockchain</li>
<li>AI dan machine learning integration</li>
<li>Semantic understanding</li>
<li>Enhanced privacy dan security</li>
</ul>

<blockquote>
"The web as I envisioned it, we have not seen it yet. The future is still so much bigger than the past." - Tim Berners-Lee
</blockquote>',
            'urutan' => 1,
        ];

        // Modul 2: HTML Fundamentals
        $materiData[] = [
            'modul_id' => $moduls[1]->id,
            'judul' => 'HTML Tags Reference',
            'deskripsi' => 'Referensi lengkap HTML tags yang paling sering digunakan',
            'konten' => '<h1>HTML Tags Reference</h1>
<p>Berikut adalah daftar HTML tags yang paling sering digunakan dalam web development.</p>

<h2>Document Structure</h2>
<pre><code>&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;Page Title&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
    Content here
  &lt;/body&gt;
&lt;/html&gt;</code></pre>

<h2>Text Formatting</h2>
<ul>
<li><code>&lt;h1&gt;</code> to <code>&lt;h6&gt;</code> - Headings</li>
<li><code>&lt;p&gt;</code> - Paragraph</li>
<li><code>&lt;strong&gt;</code> - Bold text (important)</li>
<li><code>&lt;em&gt;</code> - Italic text (emphasized)</li>
<li><code>&lt;br&gt;</code> - Line break</li>
<li><code>&lt;hr&gt;</code> - Horizontal rule</li>
</ul>

<h2>Links dan Images</h2>
<pre><code>&lt;a href="https://example.com"&gt;Link Text&lt;/a&gt;
&lt;img src="image.jpg" alt="Description"&gt;</code></pre>

<h2>Lists</h2>
<pre><code>&lt;ul&gt;
  &lt;li&gt;Unordered item&lt;/li&gt;
&lt;/ul&gt;

&lt;ol&gt;
  &lt;li&gt;Ordered item&lt;/li&gt;
&lt;/ol&gt;</code></pre>

<h2>Semantic HTML5 Tags</h2>
<ul>
<li><code>&lt;header&gt;</code> - Header section</li>
<li><code>&lt;nav&gt;</code> - Navigation</li>
<li><code>&lt;main&gt;</code> - Main content</li>
<li><code>&lt;article&gt;</code> - Independent content</li>
<li><code>&lt;section&gt;</code> - Thematic grouping</li>
<li><code>&lt;aside&gt;</code> - Sidebar content</li>
<li><code>&lt;footer&gt;</code> - Footer section</li>
</ul>',
            'urutan' => 1,
        ];

        $materiData[] = [
            'modul_id' => $moduls[1]->id,
            'judul' => 'Best Practices HTML',
            'deskripsi' => 'Panduan menulis HTML yang clean dan maintainable',
            'konten' => '<h1>Best Practices HTML</h1>
<p>Menulis HTML yang baik bukan hanya tentang membuat website yang berfungsi, tetapi juga tentang membuat code yang mudah dipahami dan di-maintain.</p>

<h2>1. Gunakan Semantic HTML</h2>
<p>Gunakan tags yang sesuai dengan konten:</p>
<pre><code>❌ Bad:
&lt;div class="header"&gt;...&lt;/div&gt;
&lt;div class="nav"&gt;...&lt;/div&gt;

✅ Good:
&lt;header&gt;...&lt;/header&gt;
&lt;nav&gt;...&lt;/nav&gt;</code></pre>

<h2>2. Indentasi yang Konsisten</h2>
<p>Gunakan 2 atau 4 spasi untuk indentasi:</p>
<pre><code>&lt;div&gt;
  &lt;p&gt;Properly indented&lt;/p&gt;
  &lt;ul&gt;
    &lt;li&gt;Item 1&lt;/li&gt;
    &lt;li&gt;Item 2&lt;/li&gt;
  &lt;/ul&gt;
&lt;/div&gt;</code></pre>

<h2>3. Alt Text untuk Images</h2>
<p>Selalu sertakan alt text untuk accessibility:</p>
<pre><code>&lt;img src="logo.png" alt="Company Logo"&gt;</code></pre>

<h2>4. Lowercase Tags dan Attributes</h2>
<pre><code>❌ Bad: &lt;DIV CLASS="container"&gt;
✅ Good: &lt;div class="container"&gt;</code></pre>

<h2>5. Close All Tags</h2>
<p>Pastikan semua tags ditutup dengan benar, termasuk self-closing tags:</p>
<pre><code>&lt;img src="photo.jpg" alt="Photo" /&gt;
&lt;br /&gt;
&lt;input type="text" /&gt;</code></pre>

<h2>6. Validasi HTML</h2>
<p>Gunakan <a href="https://validator.w3.org/" target="_blank">W3C Validator</a> untuk check HTML kamu.</p>',
            'urutan' => 2,
        ];

        // Modul 3: CSS Styling & Layouts
        $materiData[] = [
            'modul_id' => $moduls[2]->id,
            'judul' => 'CSS Box Model',
            'deskripsi' => 'Memahami konsep box model dalam CSS',
            'konten' => '<h1>CSS Box Model</h1>
<p>Box model adalah konsep fundamental dalam CSS yang menjelaskan bagaimana elemen HTML di-render sebagai rectangular boxes.</p>

<h2>Komponen Box Model</h2>
<p>Setiap box terdiri dari 4 komponen:</p>
<ol>
<li><strong>Content</strong> - Area konten aktual (teks, gambar, dll)</li>
<li><strong>Padding</strong> - Ruang antara content dan border</li>
<li><strong>Border</strong> - Garis pembatas di sekitar padding</li>
<li><strong>Margin</strong> - Ruang di luar border</li>
</ol>

<h2>Contoh Implementasi</h2>
<pre><code>.box {
  width: 300px;
  height: 200px;
  padding: 20px;
  border: 2px solid #333;
  margin: 10px;
}</code></pre>

<h2>Box-Sizing Property</h2>
<p>Secara default, width dan height hanya mengatur content box. Gunakan <code>box-sizing: border-box</code> untuk include padding dan border:</p>
<pre><code>* {
  box-sizing: border-box;
}

.box {
  width: 300px; /* Ini akan menjadi total width termasuk padding & border */
  padding: 20px;
  border: 2px solid #333;
}</code></pre>

<h2>Tips Praktis</h2>
<ul>
<li>Gunakan <code>box-sizing: border-box</code> untuk semua elemen</li>
<li>Margin bisa collapse pada vertical adjacency</li>
<li>Padding tidak bisa negative, margin bisa</li>
<li>Gunakan developer tools untuk visualize box model</li>
</ul>',
            'urutan' => 1,
        ];

        // Modul 4: JavaScript Basics
        $materiData[] = [
            'modul_id' => $moduls[3]->id,
            'judul' => 'JavaScript Array Methods',
            'deskripsi' => 'Method-method penting untuk memanipulasi array',
            'konten' => '<h1>JavaScript Array Methods</h1>
<p>Array methods adalah fungsi built-in yang memudahkan manipulasi data dalam array.</p>

<h2>1. map() - Transform Array</h2>
<p>Membuat array baru dengan transform setiap element:</p>
<pre><code>const numbers = [1, 2, 3, 4];
const doubled = numbers.map(num => num * 2);
// Result: [2, 4, 6, 8]</code></pre>

<h2>2. filter() - Filter Array</h2>
<p>Membuat array baru dengan element yang memenuhi kondisi:</p>
<pre><code>const numbers = [1, 2, 3, 4, 5, 6];
const evenNumbers = numbers.filter(num => num % 2 === 0);
// Result: [2, 4, 6]</code></pre>

<h2>3. reduce() - Aggregate Values</h2>
<p>Menggabungkan semua element menjadi single value:</p>
<pre><code>const numbers = [1, 2, 3, 4];
const sum = numbers.reduce((acc, num) => acc + num, 0);
// Result: 10</code></pre>

<h2>4. find() - Find Element</h2>
<p>Mencari element pertama yang memenuhi kondisi:</p>
<pre><code>const users = [
  { id: 1, name: "John" },
  { id: 2, name: "Jane" }
];
const user = users.find(u => u.id === 2);
// Result: { id: 2, name: "Jane" }</code></pre>

<h2>5. forEach() - Iterate Array</h2>
<p>Execute function untuk setiap element:</p>
<pre><code>const fruits = ["apple", "banana", "orange"];
fruits.forEach(fruit => {
  console.log(fruit);
});</code></pre>

<h2>6. some() & every()</h2>
<pre><code>const numbers = [1, 2, 3, 4, 5];

// some: at least one element meets condition
numbers.some(num => num > 3); // true

// every: all elements meet condition
numbers.every(num => num > 0); // true</code></pre>

<blockquote>
💡 Tip: Array methods ini sangat powerful untuk functional programming dan lebih readable dibanding loop tradisional.
</blockquote>',
            'urutan' => 1,
        ];

        // Modul 5: JavaScript Advanced
        $materiData[] = [
            'modul_id' => $moduls[4]->id,
            'judul' => 'Understanding Promises',
            'deskripsi' => 'Deep dive ke JavaScript Promises dan error handling',
            'konten' => '<h1>Understanding Promises</h1>
<p>Promise adalah object yang merepresentasikan eventual completion (atau failure) dari asynchronous operation.</p>

<h2>Promise States</h2>
<p>Promise memiliki 3 states:</p>
<ul>
<li><strong>Pending</strong> - Initial state, belum fulfilled atau rejected</li>
<li><strong>Fulfilled</strong> - Operation berhasil</li>
<li><strong>Rejected</strong> - Operation gagal</li>
</ul>

<h2>Creating a Promise</h2>
<pre><code>const myPromise = new Promise((resolve, reject) => {
  // Async operation
  setTimeout(() => {
    const success = true;
    if (success) {
      resolve("Operation successful!");
    } else {
      reject("Operation failed!");
    }
  }, 1000);
});</code></pre>

<h2>Consuming Promises</h2>
<pre><code>myPromise
  .then(result => {
    console.log(result); // "Operation successful!"
  })
  .catch(error => {
    console.error(error);
  })
  .finally(() => {
    console.log("Promise settled");
  });</code></pre>

<h2>Promise Chaining</h2>
<pre><code>fetch("/api/user")
  .then(response => response.json())
  .then(user => {
    console.log("User:", user);
    return fetch(`/api/posts/${user.id}`);
  })
  .then(response => response.json())
  .then(posts => {
    console.log("Posts:", posts);
  })
  .catch(error => {
    console.error("Error:", error);
  });</code></pre>

<h2>Promise.all() dan Promise.race()</h2>
<pre><code>// Promise.all - tunggu semua promises selesai
Promise.all([promise1, promise2, promise3])
  .then(results => {
    console.log(results); // [result1, result2, result3]
  });

// Promise.race - ambil yang paling cepat selesai
Promise.race([promise1, promise2, promise3])
  .then(result => {
    console.log(result); // result dari promise tercepat
  });</code></pre>

<h2>Async/Await - Modern Syntax</h2>
<pre><code>async function fetchUser() {
  try {
    const response = await fetch("/api/user");
    const user = await response.json();
    console.log(user);
  } catch (error) {
    console.error("Error:", error);
  }
}</code></pre>',
            'urutan' => 1,
        ];

        foreach ($materiData as $materi) {
            Materi::create($materi);
        }

        $this->command->info('Materi Web Development berhasil dibuat!');
    }
}
