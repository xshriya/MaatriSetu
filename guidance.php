<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    // User is not logged in, redirect to login page
    header('Location: login.html?message=Please log in to access the Guidance page');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnancy Guidance - MaatriSetu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pink: {
                            600: '#ec4899',
                            700: '#be185d'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-pink-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow py-4 mb-6">
        <div class="container mx-auto flex items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <img src="logo.jpeg" alt="MaatriSetu Logo" class="h-10 w-auto" />
                <span class="text-xl font-bold text-pink-600">MaatriSetu</span>
            </div>
            <nav>
                <ul class="flex gap-6 text-base font-medium">
                    <li><a href="index.php" class="hover:text-pink-600">Home</a></li>
                    <li><a href="community.php" class="hover:text-pink-600">Community</a></li>
                    <li><a href="guidance.php" class="text-pink-600 font-semibold">Guidance</a></li>
                    <li><a href="schemes.php" class="hover:text-pink-600">Schemes</a></li>
                    <li><a href="reminders.php" class="hover:text-pink-600">Reminders</a></li>
                    <li><a href="f-a-q.html" class="hover:text-pink-600">FAQ</a></li>
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <li class="relative">
                            <button id="userMenuButton" class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </button>
                            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Settings</a>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login.html" class="hover:text-pink-600">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-6">
        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold text-pink-600 mb-2">Pregnancy Guidance - Week by Week</h1>
            <p class="text-gray-600">Select a week to see symptoms, baby development, body changes, medical checks, and pro tips.</p>
        </div>

        <!-- Week Selector -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Select Pregnancy Week</h2>
            <div class="flex flex-wrap gap-2" id="weekSelector"></div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <article class="bg-white rounded-lg shadow-md p-6" id="contentArea">
                    <!-- Filled by JavaScript -->
                </article>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-4">
                <!-- Quick Links -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold text-pink-600 mb-3">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#symptoms" class="text-blue-600 hover:underline">Symptoms</a></li>
                        <li><a href="#baby" class="text-blue-600 hover:underline">Your Baby</a></li>
                        <li><a href="#body" class="text-blue-600 hover:underline">Your Body</a></li>
                        <li><a href="#scans" class="text-blue-600 hover:underline">Medical Checks</a></li>
                    </ul>
                </div>

                <!-- Food Guidance -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold text-pink-600 mb-3">Food Guidance</h3>
                    <ul id="foodList" class="space-y-1 text-sm text-gray-700 list-disc list-inside"></ul>
                </div>

                <!-- Medicines & Vitamins -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold text-pink-600 mb-3">Medicines & Vitamins</h3>
                    <ul id="medList" class="space-y-1 text-sm text-gray-700 list-disc list-inside"></ul>
                </div>

                <!-- Exercise -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold text-pink-600 mb-3">Exercise / Movement</h3>
                    <ul id="exList" class="space-y-1 text-sm text-gray-700 list-disc list-inside"></ul>
                </div>

                <!-- Self-care -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold text-pink-600 mb-3">Self-care Tips</h3>
                    <ul id="selfList" class="space-y-1 text-sm text-gray-700 list-disc list-inside"></ul>
                </div>

                <!-- Safety Note -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-red-600 mb-2">Safety Note</h3>
                    <p class="text-sm text-gray-700">If you have heavy bleeding, severe pain, fever, or reduced baby movement — contact your healthcare provider immediately.</p>
                </div>
            </aside>
        </div>
    </div>

    <footer class="text-center py-6 text-gray-600 text-sm mt-8">
        © 2025 MaatriSetu — Supporting Every Step of Motherhood
    </footer>

<script>
/* Data for weeks 1–5 with detailed info under each topic */
const weeks = {
  1: {
    summary: "Week 1: (Conception window) Very early changes as the body prepares for implantation.",
    symptoms: ["Often no symptoms yet", "Mild cramping or spotting in some"],
    baby: "Fertilization and first cell divisions occur; a blastocyst will implant in the uterine lining soon.",
    body: "Hormone levels begin to change; you may feel tired or notice breast tenderness.",
    scans: [],
    proTips: ["Start or continue folic acid (400–800 mcg daily) to support neural tube formation.", "Avoid alcohol and smoking immediately.", "Eat balanced meals — include fruits, vegetables and whole grains."],
    essentials: { food:["Leafy greens (spinach, fenugreek)", "Citrus fruits (vitamin C)", "Whole grains"], meds:["Folic acid supplement", "Continue any prescribed meds after consulting doctor"], exercise:["Gentle walking 20–30 min", "Avoid vigorous new routines"], self:["Rest when tired", "Manage stress with short breathing breaks"] }
  },

  2: {
    summary: "Week 2: Implantation may occur; early pregnancy hormones rise.",
    symptoms: ["Light implantation spotting", "Fatigue, mood changes"],
    baby: "Blastocyst embeds in the uterine lining and early placenta tissues start forming.",
    body: "Rising hCG and progesterone may cause tiredness and subtle changes in appetite.",
    scans: ["Blood hCG test may confirm pregnancy"],
    proTips: ["Keep taking folic acid and a prenatal vitamin.", "Stay hydrated and eat small frequent meals if nauseous.", "Avoid high-mercury fish and raw/undercooked foods."],
    essentials: { food:["Iron-rich legumes (lentils, chickpeas)", "Yogurt for calcium and probiotics"], meds:["Prenatal multivitamin", "Discuss current prescriptions with provider"], exercise:["Short gentle walks", "Stretching for comfort"], self:["Track symptoms in a journal", "Tell a trusted contact if needed"] }
  },

  3: {
    summary: "Week 3: Embryo formation begins; foundational tissues are laid down.",
    symptoms: ["Nausea may start for some", "Breast tenderness and frequent urination"],
    baby: "Germ layers form which will become the brain, heart, and other organs; placenta develops further.",
    body: "Increased blood volume and hormones may lead to mild dizziness; rest and small meals help.",
    scans: [],
    proTips: ["Eat iron and folate rich foods to support blood volume expansion.", "Avoid unpasteurized cheeses and deli meats unless heated.", "Start pelvic floor awareness (gentle contractions) but no intense exercises."],
    essentials: { food:["Green vegetables (fenugreek/spinach)", "Pulses & legumes", "Fresh fruit"], meds:["Continue prenatal vitamin", "Iron if prescribed by doctor"], exercise:["Short walk after meals to help digestion", "Gentle pelvic floor activation"], self:["Practice mindful breathing (5 min/day)", "Keep hydration bottle handy"] }
  },

  4: {
    summary: "Week 4: Pregnancy is usually detectable by home test; embryo growth continues.",
    symptoms: ["Missed period is common", "Nausea, tiredness, mild cramps"],
    baby: "Placenta and embryo develop; early heart tube formation occurs.",
    body: "Hormonal symptoms typically increase; protect from infections and avoid risky substances.",
    scans: ["Confirmatory ultrasound may be offered if indicated"],
    proTips: ["Schedule first prenatal appointment if you haven't already.", "Focus on balanced meals; include protein and vitamin C to boost iron absorption.", "Sleep on left side later in pregnancy; for now, find comfortable positions and pillows."],
    essentials: { food:["Lean protein (eggs, beans)", "Citrus for vitamin C", "Whole grain bread/cereal"], meds:["Folic acid + prenatal multivitamin", "Avoid non-prescribed medications"], exercise:["Gentle yoga for relaxation (avoid intense poses)","Walking 20–30 min"], self:["Start a pregnancy notes file (appointments, meds)", "Speak to your clinician about any concerns"] }
  },

  5: {
    summary: "Week 5: Heart tube may start beating soon; major organ foundations form.",
    symptoms: ["Nausea may increase", "Food aversions or cravings"],
    baby: "Early heart and neural development continues; embryo grows rapidly this week.",
    body: "You may feel more tired; small frequent meals and rest help manage symptoms.",
    scans: ["Dating ultrasound may be considered if dates unsure"],
    proTips: ["Maintain consistent prenatal vitamin intake (folic acid & iron).", "Manage nausea with bland, frequent snacks (crackers, bananas).", "Avoid heavy lifting and consult your provider about travel if concerned."],
    essentials: { food:["Bland snack options (crackers/bananas) for nausea", "Protein snacks (nuts, paneer)"], meds:["Prenatal vitamin + iron as advised", "Only take OTC meds after clinician advice"], exercise:["Gentle walking; avoid overheating", "Light stretching for comfort"], self:["Rest when needed", "Hydrate with small sips frequently"] }
  },

  6: {
    summary: "Week 6: Heartbeat may be detected; early organ development continues.",
    symptoms: ["Nausea may persist or increase", "Breast tenderness continues", "Fatigue"],
    baby: "Major organs start forming; tiny heart begins regular beating.",
    body: "Hormonal changes can cause mood swings and mild dizziness.",
    scans: ["Ultrasound may detect heartbeat"],
    proTips: ["Maintain consistent prenatal vitamin intake", "Eat small, frequent meals to manage nausea", "Avoid raw/undercooked foods and alcohol"],
    essentials: { food:["Protein-rich foods (eggs, beans)", "Fruits rich in vitamin C"], meds:["Prenatal vitamin + iron if advised"], exercise:["Gentle walking, light stretching"], self:["Rest when tired", "Stay hydrated"] }
  },

  7: {
    summary: "Week 7: Embryo grows; limb buds appear.",
    symptoms: ["Morning sickness", "Increased urination", "Mood swings"],
    baby: "Arms and legs begin forming; brain and spinal cord continue development.",
    body: "Fatigue and mild cramping are common; pay attention to diet.",
    scans: ["Optional early ultrasound for dating"],
    proTips: ["Continue folic acid and prenatal vitamins", "Snack on bland foods to ease nausea", "Practice gentle stretches for back discomfort"],
    essentials: { food:["Leafy greens", "Whole grains", "Yogurt for calcium"], meds:["Prenatal vitamins as prescribed"], exercise:["Short walks", "Gentle stretching"], self:["Track symptoms in a journal", "Relaxation breathing"] }
  },

  8: {
    summary: "Week 8: Facial features begin forming; organs continue development.",
    symptoms: ["Nausea, bloating", "Frequent urination", "Breast tenderness"],
    baby: "Embryo is forming facial features; heart and liver function continues.",
    body: "Hormonal changes can affect appetite and sleep.",
    scans: ["Ultrasound may visualize embryo"],
    proTips: ["Stay hydrated and maintain balanced meals", "Avoid caffeine and alcohol", "Gentle exercise can help energy levels"],
    essentials: { food:["Protein and fiber-rich foods", "Fruits and vegetables"], meds:["Prenatal vitamins", "Iron supplements if prescribed"], exercise:["Walking 20–30 min", "Light yoga for relaxation"], self:["Short naps if tired", "Mindfulness exercises"] }
  },

  9: {
    summary: "Week 9: Embryo officially called a fetus; bones start forming.",
    symptoms: ["Fatigue", "Mood swings", "Morning sickness persists"],
    baby: "Fetus has fingers, toes, and developing bones; vital organs continue maturing.",
    body: "Energy dips; mild cramping and nausea common.",
    scans: ["Optional ultrasound to check growth and heartbeat"],
    proTips: ["Eat iron-rich foods to support blood volume", "Continue prenatal vitamins", "Avoid strenuous activity"],
    essentials: { food:["Lean protein (chicken, beans)", "Vitamin C fruits"], meds:["Prenatal vitamin + iron"], exercise:["Gentle walking", "Light stretching"], self:["Stay hydrated", "Rest when fatigued"] }
  },

  10: {
    summary: "Week 10: Fetus grows rapidly; organs start functioning.",
    symptoms: ["Nausea may reduce for some", "Fatigue", "Breast tenderness"],
    baby: "Organs like kidneys and intestines begin working; heart fully formed.",
    body: "Hormonal adjustments continue; energy levels may fluctuate.",
    scans: ["Ultrasound may check growth and detect heartbeat"],
    proTips: ["Maintain balanced diet with protein, iron, and calcium", "Continue vitamins", "Avoid exposure to harmful substances"],
    essentials: { food:["Vegetables, fruits, whole grains"], meds:["Prenatal vitamins"], exercise:["Gentle yoga, walking"], self:["Mindfulness and light breathing exercises"] }
  },

  11: {
    summary: "Week 11: Fetus grows rapidly; reflexes begin.",
    symptoms: ["Nausea may ease", "Fatigue", "Mood swings continue"],
    baby: "Fingers, toes, and bones continue to develop; reflexes start to appear.",
    body: "Uterus grows; some may experience mild abdominal discomfort.",
    scans: ["Nuchal translucency scan may be offered around this time"],
    proTips: ["Maintain balanced diet with protein and calcium", "Stay hydrated and rest when needed", "Avoid strenuous activity"],
    essentials: { food:["Leafy greens, beans, yogurt", "Fruits and whole grains"], meds:["Prenatal vitamins + iron if prescribed"], exercise:["Gentle walking or swimming", "Light stretching"], self:["Mindfulness exercises", "Track appointments"] }
  },

  12: {
    summary: "Week 12: End of first trimester; fetal movements begin internally.",
    symptoms: ["Nausea often decreases", "Energy may improve", "Breast tenderness"],
    baby: "Fetus starts small movements; organs continue maturing; fingers and toes fully separated.",
    body: "Hormonal changes stabilize; some may notice a small baby bump.",
    scans: ["Ultrasound can confirm fetal development and heartbeat"],
    proTips: ["Continue prenatal vitamins", "Eat iron and fiber-rich foods to prevent constipation", "Maintain light physical activity"],
    essentials: { food:["Lean protein, fruits, vegetables", "Whole grains"], meds:["Prenatal vitamins"], exercise:["Walking, gentle yoga"], self:["Rest when fatigued", "Monitor for unusual symptoms"] }
  },

  13: {
    summary: "Week 13: Growth accelerates; placenta fully forms.",
    symptoms: ["Energy may return", "Mild bloating", "Occasional cramps"],
    baby: "Fetus continues organ maturation; vocal cords and facial muscles develop.",
    body: "Weight gain may begin; digestion may slow slightly.",
    scans: ["Ultrasound to check growth and anatomy"],
    proTips: ["Eat small frequent meals to avoid bloating", "Stay hydrated", "Continue prenatal care visits"],
    essentials: { food:["Protein-rich foods", "Fresh fruits and vegetables"], meds:["Prenatal vitamins"], exercise:["Gentle walking or swimming"], self:["Rest, manage stress with short breathing breaks"] }
  },

  14: {
    summary: "Week 14: Fetus grows rapidly; skeleton becomes firmer.",
    symptoms: ["Mild headaches", "Increased appetite", "Occasional dizziness"],
    baby: "Fetus develops fingernails, toenails, and bones harden.",
    body: "Uterus expands; some may feel mild back discomfort.",
    scans: ["Ultrasound may check anatomy and growth"],
    proTips: ["Maintain a balanced diet with calcium and iron", "Avoid prolonged standing", "Stay active with gentle exercises"],
    essentials: { food:["Dairy products, leafy greens, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, stretching"], self:["Mindfulness, hydration"] }
  },

  15: {
    summary: "Week 15: Rapid fetal growth; mother’s belly more noticeable.",
    symptoms: ["Backache", "Leg cramps", "Occasional heartburn"],
    baby: "Fetus’s skin is translucent; muscles strengthen; skeleton hardens.",
    body: "Weight gain continues; posture adjustments may be needed.",
    scans: ["Ultrasound may check fetal anatomy"],
    proTips: ["Maintain good posture", "Eat iron-rich foods to support growing blood volume", "Continue prenatal vitamins"],
    essentials: { food:["Leafy greens, lean protein, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking, stretching"], self:["Rest when tired", "Practice relaxation techniques"] }
  },

  16: {
    summary: "Week 16: Baby’s movements may be felt (quickening).",
    symptoms: ["Mild swelling in feet", "Fatigue", "Occasional dizziness"],
    baby: "Fetus starts coordinated movements; eyes and ears develop further.",
    body: "Uterus grows; some may feel early fetal kicks.",
    scans: ["Ultrasound can check fetal anatomy and growth"],
    proTips: ["Monitor weight gain and posture", "Eat a balanced diet with calcium and protein", "Stay hydrated and active"],
    essentials: { food:["Lean protein, dairy, fruits, vegetables"], meds:["Prenatal vitamins"], exercise:["Gentle exercises, walking"], self:["Relaxation, stretching"] }
  },

  17: {
    summary: "Week 17: Rapid growth continues; fetal movements increase.",
    symptoms: ["Mild backache", "Increased appetite", "Leg cramps"],
    baby: "Fetus’s skeleton continues to harden; nervous system develops further.",
    body: "Abdominal muscles stretch; posture adjustments recommended.",
    scans: ["Ultrasound may assess fetal growth and anatomy"],
    proTips: ["Practice gentle stretching to relieve back pain", "Eat iron-rich foods", "Maintain regular prenatal visits"],
    essentials: { food:["Vegetables, lean protein, fruits, whole grains"], meds:["Prenatal vitamins"], exercise:["Walking, light yoga"], self:["Hydration and rest"] }
  },

  18: {
    summary: "Week 18: Fetal movements more noticeable; gender may be identified.",
    symptoms: ["Leg cramps", "Backache", "Fatigue"],
    baby: "Fetus develops fat beneath skin; hearing improves.",
    body: "Uterus continues to expand; mild swelling possible.",
    scans: ["Anatomy scan typically done around this week"],
    proTips: ["Maintain balanced diet", "Stay active with gentle exercises", "Wear comfortable shoes to reduce swelling"],
    essentials: { food:["Protein, leafy greens, fruits, whole grains"], meds:["Prenatal vitamins"], exercise:["Gentle walking, stretching"], self:["Relaxation, hydration"] }
  },

  19: {
    summary: "Week 19: Rapid growth; brain and muscles develop quickly.",
    symptoms: ["Increased appetite", "Fatigue", "Back discomfort"],
    baby: "Fetus’s senses improve; fine hair (lanugo) develops.",
    body: "Weight gain continues; posture changes may cause back pain.",
    scans: ["Anatomy scan to check organs and development"],
    proTips: ["Continue prenatal vitamins", "Eat small frequent meals with protein and fiber", "Practice good posture and gentle stretching"],
    essentials: { food:["Lean protein, fruits, vegetables, whole grains"], meds:["Prenatal vitamins"], exercise:["Gentle walking, stretching"], self:["Relaxation, hydration"] }
  },

  20: {
    summary: "Week 20: Halfway point of pregnancy; baby is very active.",
    symptoms: ["Increased fetal movements", "Backache", "Fatigue"],
    baby: "Fetus develops taste buds; hair, nails, and skin layers form.",
    body: "Uterus is at the belly button; weight gain continues steadily.",
    scans: ["Mid-pregnancy ultrasound for anatomy and growth"],
    proTips: ["Eat balanced meals with protein, calcium, and iron", "Stay active and maintain posture", "Continue prenatal care visits"],
    essentials: { food:["Leafy greens, fruits, lean protein, dairy"], meds:["Prenatal vitamins"], exercise:["Walking, stretching, light yoga"], self:["Rest when tired, stay hydrated"] }
  },
  // Weeks 21–40
21: {
  summary: "Week 21: Baby kicks more frequently; growth continues.",
  symptoms: ["Backache", "Increased appetite", "Swelling in feet"],
  baby: "Fetus develops fine hair (lanugo) and fat begins to accumulate under skin.",
  body: "Uterus expands; posture may change, causing mild discomfort.",
  scans: ["Growth ultrasound may be offered if needed"],
  proTips: ["Eat balanced meals with protein and fiber", "Stay active with gentle exercises", "Wear supportive shoes"],
  essentials: { food:["Lean protein, vegetables, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, light yoga"], self:["Rest and hydrate"] }
},

22: {
  summary: "Week 22: Rapid growth; movements become stronger.",
  symptoms: ["Braxton Hicks contractions may begin", "Back pain", "Fatigue"],
  baby: "Fetus swallows amniotic fluid and produces meconium; skin is wrinkled but developing fat.",
  body: "Uterus rises; some may experience heartburn or mild swelling.",
  scans: ["Ultrasound may check growth and anatomy"],
  proTips: ["Maintain good posture", "Eat small frequent meals", "Stay hydrated"],
  essentials: { food:["Whole grains, lean protein, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Relaxation, short naps"] }
},

23: {
  summary: "Week 23: Baby's senses develop further; kicks and movements are stronger.",
  symptoms: ["Mild backache", "Leg cramps", "Fatigue"],
  baby: "Fetus develops taste buds; lungs continue developing, though not fully functional.",
  body: "Abdominal muscles stretch; swelling may occur.",
  scans: ["Ultrasound may assess growth if needed"],
  proTips: ["Eat iron-rich foods", "Continue prenatal vitamins", "Practice gentle stretches"],
  essentials: { food:["Vegetables, lean protein, dairy, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, light stretching"], self:["Hydrate, rest when tired"] }
},

24:{
  summary: "Week 24: Baby gains weight rapidly; skin thickens.",
  symptoms: ["Braxton Hicks contractions may occur", "Heartburn", "Backache"],
  baby: "Lungs produce surfactant; movements are noticeable and coordinated.",
  body: "Uterus rises; back pain may increase; mild swelling in feet or hands.",
  scans: ["Ultrasound to check growth if needed"],
  proTips: ["Maintain balanced diet with calcium and iron", "Stay active but avoid overexertion", "Wear supportive shoes"],
  essentials: { food:["Leafy greens, fruits, dairy, protein"], meds:["Prenatal vitamins"], exercise:["Walking, gentle yoga"], self:["Rest, hydration"] }
},

25:{
  summary: "Week 25: Baby responds to sounds; kicks are stronger.",
  symptoms: ["Backache", "Leg cramps", "Fatigue", "Heartburn"],
  baby: "Fetus develops more fat under the skin; lungs continue maturing.",
  body: "Uterus continues to expand; posture changes can cause mild discomfort.",
  scans: ["Growth scan if indicated"],
  proTips: ["Eat iron-rich foods and stay hydrated", "Use supportive pillows while sleeping", "Avoid prolonged standing"],
  essentials: { food:["Lean protein, fruits, vegetables, whole grains"], meds:["Prenatal vitamins"], exercise:["Walking, stretching"], self:["Rest and relaxation"] }
},

26: {
  summary: "Week 26: Baby's eyes open and close; rapid growth continues.",
  symptoms: ["Swelling in feet", "Braxton Hicks contractions", "Back discomfort"],
  baby: "Fetus develops eyelashes and eyebrows; nervous system continues developing.",
  body: "Uterus expands; mild shortness of breath may occur.",
  scans: ["Ultrasound if needed to assess growth"],
  proTips: ["Maintain balanced diet", "Practice gentle stretching", "Stay hydrated"],
  essentials: { food:["Dairy, leafy greens, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest and hydration"] }
},

27:{
  summary: "Week 27: Third trimester begins; baby's movements are strong.",
  symptoms: ["Back pain", "Fatigue", "Heartburn", "Leg cramps"],
  baby: "Lungs continue maturing; brain development accelerates.",
  body: "Uterus reaches ribs; balance may be affected.",
  scans: ["Ultrasound if indicated"],
  proTips: ["Eat small frequent meals", "Maintain posture", "Practice relaxation techniques"],
  essentials: { food:["Protein, fruits, vegetables"], meds:["Prenatal vitamins"], exercise:["Walking, light yoga"], self:["Hydration, rest"] }
},

28: {
  summary: "Week 28: Baby practices breathing; fat accumulates under skin.",
  symptoms: ["Braxton Hicks contractions", "Backache", "Leg cramps"],
  baby: "Eyes can open/close; lungs continue developing surfactant.",
  body: "Uterus rises further; occasional shortness of breath.",
  scans: ["Ultrasound may be done if indicated"],
  proTips: ["Stay active with gentle movement", "Eat iron and calcium-rich foods", "Continue prenatal vitamins"],
  essentials: { food:["Dairy, vegetables, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest and hydration"] }
},

29: {
  summary: "Week 29: Baby's bones are fully developed; muscles strengthen.",
  symptoms: ["Fatigue", "Back pain", "Leg cramps", "Heartburn"],
  baby: "Brain development accelerates; baby responds to sounds and light.",
  body: "Uterus continues to expand; balance may be affected.",
  scans: ["Ultrasound if indicated"],
  proTips: ["Practice gentle stretching", "Maintain balanced diet", "Stay hydrated"],
  essentials: { food:["Lean protein, leafy greens, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, light yoga"], self:["Rest and relaxation"] }
},

30: {
  summary: "Week 30: Baby gains weight quickly; movements strong.",
  symptoms: ["Backache", "Swelling", "Braxton Hicks contractions"],
  baby: "Baby’s body fat increases; lungs mature; sleep-wake cycles develop.",
  body: "Uterus is high; some may experience shortness of breath or fatigue.",
  scans: ["Growth ultrasound if needed"],
  proTips: ["Eat balanced meals", "Practice posture-friendly movements", "Stay hydrated"],
  essentials: { food:["Protein, dairy, fruits, vegetables"], meds:["Prenatal vitamins"], exercise:["Gentle walking, stretching"], self:["Rest and hydration"] }
},

31:{
  summary: "Week 31: Baby’s bones harden; body fat accumulates.",
  symptoms: ["Back pain", "Fatigue", "Swelling", "Heartburn"],
  baby: "Baby’s nervous system matures; kicks and movements are strong.",
  body: "Uterus continues to expand; balance may be affected.",
  scans: ["Ultrasound if indicated"],
  proTips: ["Maintain balanced diet", "Practice gentle stretching", "Stay hydrated"],
  essentials: { food:["Lean protein, leafy greens, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, light yoga"], self:["Rest and relaxation"] }
},

32:{
  summary: "Week 32: Baby’s movements are strong; lungs develop further.",
  symptoms: ["Braxton Hicks contractions", "Leg cramps", "Fatigue"],
  baby: "Lungs continue developing; baby begins storing fat for temperature regulation.",
  body: "Uterus may press on diaphragm; shortness of breath possible.",
  scans: ["Ultrasound if needed for growth and position"],
  proTips: ["Eat small frequent meals", "Stay hydrated", "Practice relaxation techniques"],
  essentials: { food:["Protein, fruits, vegetables"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest and hydration"] }
},

33:  {
  summary: "Week 33: Baby’s skull bones remain soft for birth; body fat increases.",
  symptoms: ["Back pain", "Swelling", "Braxton Hicks contractions"],
  baby: "Baby gains more fat; lungs continue maturing.",
  body: "Uterus is high; pressure on bladder may increase.",
  scans: ["Ultrasound if indicated"],
  proTips: ["Maintain good posture", "Eat iron and calcium-rich foods", "Stay hydrated"],
  essentials: { food:["Dairy, lean protein, vegetables, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, gentle yoga"], self:["Rest and relaxation"] }
},

34:{
  summary: "Week 34: Baby positions head-down for birth; movements strong.",
  symptoms: ["Braxton Hicks contractions", "Fatigue", "Swelling"],
  baby: "Baby continues gaining weight; brain and lungs mature.",
  body: "Pelvic pressure increases; posture may be affected.",
  scans: ["Ultrasound if needed"],
  proTips: ["Practice safe posture", "Eat balanced meals", "Stay hydrated"],
  essentials: { food:["Lean protein, vegetables, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest and hydration"] }
},

35:{
  summary: "Week 35: Baby gains about half a pound per week; movements frequent.",
  symptoms: ["Fatigue", "Back pain", "Braxton Hicks contractions"],
  baby: "Baby’s organs are nearly fully developed; body fat increases.",
  body: "Pelvic pressure may cause discomfort; shortness of breath possible.",
  scans: ["Ultrasound if needed"],
  proTips: ["Maintain balanced diet", "Use pillows for support", "Practice gentle stretching"],
  essentials: { food:["Protein, leafy greens, fruits"], meds:["Prenatal vitamins"], exercise:["Walking, light yoga"], self:["Rest and hydration"] }
},

36: {
  summary: "Week 36: Baby is almost full-term; organs ready for birth.",
  symptoms: ["Increased pelvic pressure", "Braxton Hicks contractions", "Fatigue"],
  baby: "Baby continues storing fat; lungs fully develop.",
  body: "Uterus is lower; more pressure on bladder and pelvis.",
  scans: ["Ultrasound if indicated"],
  proTips: ["Prepare for birth", "Maintain hydration and rest", "Monitor fetal movements"],
  essentials: { food:["Lean protein, fruits, vegetables"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest and hydration"] }
},
37: {
  summary: "Week 37: Baby considered early term; strong movements.",
  symptoms: ["Braxton Hicks contractions", "Pelvic pressure", "Fatigue"],
  baby: "Baby gains weight; skin smooths; movements strong.",
  body: "Uterus presses on diaphragm less but pelvis feels heavy.",
  scans: ["Ultrasound if needed"],
  proTips: ["Prepare hospital bag", "Stay active gently", "Continue prenatal vitamins"],
  essentials: { food:["Protein, dairy, vegetables, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest and hydration"] }
},

38:{
  summary: "Week 38: Baby descends into pelvis (lightening); movements strong.",
  symptoms: ["Pelvic pressure", "Braxton Hicks contractions", "Fatigue"],
  baby: "Baby’s organs are fully mature; lanugo sheds.",
  body: "Uterus lower; more pelvic pressure; monitor contractions.",
  scans: ["Ultrasound if indicated"],
  proTips: ["Monitor baby movements", "Rest often", "Prepare for labor signs"],
  essentials: { food:["Balanced diet, protein, fruits"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest, hydration"] }
},

39:{
  summary: "Week 39: Baby full-term; ready for birth.",
  symptoms: ["Strong pelvic pressure", "Braxton Hicks contractions", "Fatigue"],
  baby: "Baby continues to gain fat; organs ready for life outside womb.",
  body: "Uterus lower; monitor for labor signs.",
  scans: ["Ultrasound only if indicated"],
  proTips: ["Stay hydrated", "Monitor contractions and fetal movements", "Prepare for labor"],
  essentials: { food:["Protein, fruits, vegetables"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Rest, hydration"] }
},

40: {
  summary: "Week 40: Baby may arrive any day; full-term pregnancy.",
  symptoms: ["Braxton Hicks contractions", "Fatigue", "Pelvic pressure"],
  baby: "Baby fully developed; organs ready for birth; movements may decrease slightly due to space.",
  body: "Uterus at maximum size; prepare for labor.",
  scans: ["Ultrasound only if needed to check baby position or growth"],
  proTips: ["Rest and stay hydrated", "Watch for labor signs", "Keep hospital bag ready"],
  essentials: { food:["Light balanced meals"], meds:["Prenatal vitamins"], exercise:["Gentle walking"], self:["Relaxation, hydration"] }
},

};

/* UI elements */
const selector = document.getElementById('weekSelector');
const contentArea = document.getElementById('contentArea');
const foodList = document.getElementById('foodList');
const medList = document.getElementById('medList');
const exList = document.getElementById('exList');
const selfList = document.getElementById('selfList');

// Create week selector buttons
for(let i=1;i<=40;i++){
  const btn = document.createElement('button');
  btn.textContent = `Week ${i}`;
  btn.dataset.week = i;
  btn.className = 'px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-pink-50 hover:border-pink-600 transition-colors text-sm font-medium';
  if(i===1) {
    btn.classList.add('bg-pink-600', 'text-white', 'border-pink-600');
    btn.classList.remove('bg-white');
  }
  btn.addEventListener('click', ()=> {
    document.querySelectorAll('#weekSelector button').forEach(b=>{
      b.classList.remove('bg-pink-600', 'text-white', 'border-pink-600');
      b.classList.add('bg-white');
    });
    btn.classList.add('bg-pink-600', 'text-white', 'border-pink-600');
    btn.classList.remove('bg-white');
    renderWeek(i);
    if(window.innerWidth < 900) window.scrollTo({ top: 120, behavior: 'smooth' });
  });
  selector.appendChild(btn);
}

/* helper: create list HTML */
function mkList(arr){
  if(!arr || arr.length===0) return '<p class="text-sm text-gray-500">No items listed.</p>';
  return '<ul class="list-disc list-inside space-y-1 text-sm text-gray-700">' + arr.map(it=>`<li>${it}</li>`).join('') + '</ul>';
}

/* render week */
function renderWeek(n){
  const w = weeks[n];
  if(!w){
    contentArea.innerHTML = `<h2 class="text-2xl font-bold text-pink-600 mb-4">Week ${n}</h2><p class="text-gray-500">No content yet for this week.</p>`;
    foodList.innerHTML = medList.innerHTML = exList.innerHTML = selfList.innerHTML = '<li>—</li>';
    return;
  }

  contentArea.innerHTML = `
    <h2 class="text-2xl font-bold text-pink-600 mb-3">Week ${n}</h2>
    <p class="text-gray-600 mb-6">${w.summary}</p>

    <div class="mb-6 pb-6 border-b border-gray-200" id="symptoms">
      <h3 class="text-xl font-semibold text-gray-800 mb-3">Symptoms</h3>
      ${mkList(w.symptoms)}
      <button class="mt-3 text-sm text-pink-600 hover:underline font-medium" data-target="sym_more_${n}">Read more</button>
      <div id="sym_more_${n}" style="display:none;" class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-gray-700">
        If you experience heavy bleeding, severe pain, fever or decreased baby movements — contact your healthcare provider immediately.
      </div>
    </div>

    <div class="mb-6 pb-6 border-b border-gray-200" id="baby">
      <h3 class="text-xl font-semibold text-gray-800 mb-3">Your Baby</h3>
      <p class="text-sm text-gray-700">${w.baby}</p>
    </div>

    <div class="mb-6 pb-6 border-b border-gray-200" id="body">
      <h3 class="text-xl font-semibold text-gray-800 mb-3">Your Body</h3>
      <p class="text-sm text-gray-700">${w.body}</p>
    </div>

    <div class="mb-6 pb-6 border-b border-gray-200" id="scans">
      <h3 class="text-xl font-semibold text-gray-800 mb-3">Medical Checks / Scans</h3>
      ${mkList(w.scans)}
    </div>

    <div class="mb-6" id="protips">
      <h3 class="text-xl font-semibold text-gray-800 mb-3">Pro Tips</h3>
      ${mkList(w.proTips)}
    </div>
  `;

  /* fill sidebar essentials */
  const e = w.essentials || {};
  foodList.innerHTML = (e.food && e.food.length) ? e.food.map(x=>`<li>${x}</li>`).join('') : '<li>Healthy balanced diet</li>';
  medList.innerHTML = (e.meds && e.meds.length) ? e.meds.map(x=>`<li>${x}</li>`).join('') : '<li>Prenatal vitamin</li>';
  exList.innerHTML = (e.exercise && e.exercise.length) ? e.exercise.map(x=>`<li>${x}</li>`).join('') : '<li>Gentle walking/stretching</li>';
  selfList.innerHTML = (e.self && e.self.length) ? e.self.map(x=>`<li>${x}</li>`).join('') : '<li>Rest and hydrate</li>';

  /* readmore toggle */
  document.querySelectorAll('button[data-target]').forEach(btn=>{
    btn.onclick = ()=>{
      const id = btn.dataset.target;
      const el = document.getElementById(id);
      if(!el) return;
      el.style.display = el.style.display === 'none' ? 'block' : 'none';
      btn.textContent = el.style.display === 'none' ? 'Read more' : 'Show less';
    };
  });
}

/* initial render (week 1 selected by default) */
renderWeek(1);

// Dropdown menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenuDropdown = document.getElementById('userMenuDropdown');
    
    if (userMenuButton && userMenuDropdown) {
        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenuDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                userMenuDropdown.classList.add('hidden');
            }
        });
    }
});
</script>
</body>
</html>
