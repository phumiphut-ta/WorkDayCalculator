<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เครื่องมือคำนวณวัน</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4 text-sm">
  <div class="max-w-lg mx-auto bg-white p-4 rounded-xl shadow">
    <h1 class="text-xl font-bold mb-4 text-center">เครื่องมือคำนวณวัน</h1>

    <!-- ฟังก์ชันที่ 1 -->
    <div class="mb-6">
      <h2 class="font-semibold mb-2">1. นับจำนวนวัน</h2>
      <input type="text" id="start_date_1" placeholder="วันเริ่มต้น (ว/ด/ป พ.ศ.)" class="w-full mb-2 p-2 border rounded">
      <input type="text" id="end_date_1" placeholder="วันสิ้นสุด (ว/ด/ป พ.ศ.)" class="w-full mb-2 p-2 border rounded">
      <label class="block mb-2"><input type="checkbox" id="workdays_only_1"> นับเฉพาะวันทำการ</label>
      <button onclick="countDays()" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">คำนวณ</button>
      <div id="result_1" class="mt-2 text-green-700 font-medium"></div>
    </div>

    <!-- ฟังก์ชันที่ 2 -->
    <div>
      <h2 class="font-semibold mb-2">2. เพิ่ม/ลด วัน</h2>
      <input type="text" id="start_date_2" placeholder="วันที่เริ่มต้น (ว/ด/ป พ.ศ.)" class="w-full mb-2 p-2 border rounded">
      <select id="operation" class="w-full mb-2 p-2 border rounded">
        <option value="add">เพิ่ม</option>
        <option value="sub">ลด</option>
      </select>
      <div class="grid grid-cols-2 gap-2 mb-2">
        <input type="number" id="years" placeholder="ปี" class="p-2 border rounded">
        <input type="number" id="months" placeholder="เดือน" class="p-2 border rounded">
        <input type="number" id="weeks" placeholder="สัปดาห์" class="p-2 border rounded">
        <input type="number" id="days" placeholder="วัน" class="p-2 border rounded">
      </div>
      <label class="block mb-2"><input type="checkbox" id="workdays_only_2"> เฉพาะวันทำการ</label>
      <button onclick="calculateDate()" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">คำนวณ</button>
      <div id="result_2" class="mt-2 text-blue-700 font-medium"></div>
    </div>
  </div>

  <script>
    function parseThaiDate(dateStr) {
      const parts = dateStr.split("/");
      if (parts.length !== 3) return null;
      const [day, month, year] = parts.map(Number);
      return new Date(year - 543, month - 1, day);
    }

    function formatThaiDate(dateObj) {
      const day = dateObj.getDate().toString().padStart(2, '0');
      const month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
      const year = dateObj.getFullYear() + 543;
      return `${day}/${month}/${year}`;
    }

    function isWorkday(date) {
      const day = date.getDay();
      return day !== 0 && day !== 6; // ไม่ใช่เสาร์-อาทิตย์
    }

function countDays() {
  const start = parseThaiDate(document.getElementById("start_date_1").value);
  const end = parseThaiDate(document.getElementById("end_date_1").value);
  const workOnly = document.getElementById("workdays_only_1").checked;

  if (!start || !end || end < start) {
    document.getElementById("result_1").innerText = "กรุณากรอกวันที่ให้ถูกต้อง";
    return;
  }

  let count = 0;
  let temp = new Date(start);

  while (temp <= end) {
    if (!workOnly || isWorkday(temp)) count++;
    temp.setDate(temp.getDate() + 1);
  }

  // แปลงจำนวนวันเป็น ปี เดือน วัน โดยใช้ค่าประมาณ
  const years = Math.floor(count / 365.25);
  const remainingDaysAfterYear = count % 365.25;

  const months = Math.floor(remainingDaysAfterYear / 30.44);
  const days = Math.round(remainingDaysAfterYear % 30.44);

  const summary = `รวม ${count} วัน${workOnly ? " (วันทำการ)" : ""} นับเป็น ${years} ปี ${months} เดือน ${days} วัน`;

  document.getElementById("result_1").innerText = summary;
}

    function calculateDate() {
      const start = parseThaiDate(document.getElementById("start_date_2").value);
      if (!start) {
        document.getElementById("result_2").innerText = "กรุณากรอกวันที่เริ่มต้นให้ถูกต้อง";
        return;
      }
      let years = parseInt(document.getElementById("years").value || 0);
      let months = parseInt(document.getElementById("months").value || 0);
      let weeks = parseInt(document.getElementById("weeks").value || 0);
      let days = parseInt(document.getElementById("days").value || 0);
      const workOnly = document.getElementById("workdays_only_2").checked;
      const op = document.getElementById("operation").value;

      let result = new Date(start);
      result.setFullYear(result.getFullYear() + (op === 'add' ? years : -years));
      result.setMonth(result.getMonth() + (op === 'add' ? months : -months));

      let totalDays = days + (weeks * 7);
      if (workOnly) {
        while (totalDays > 0) {
          result.setDate(result.getDate() + (op === 'add' ? 1 : -1));
          if (isWorkday(result)) totalDays--;
        }
      } else {
        result.setDate(result.getDate() + (op === 'add' ? totalDays : -totalDays));
      }

      document.getElementById("result_2").innerText = `ผลลัพธ์: ${formatThaiDate(result)}`;
    }
  </script>
</body>
</html>
