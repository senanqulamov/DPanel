YEKUN QEYD – UAT QƏBUL SƏNƏDİ
Bu sənəd aşağıdakı məqsədlər üçün hazırlanmışdır və sistemin UAT (User Acceptance Test) mərhələsində rəsmi qəbul sənədi kimi istifadə olunmalıdır.
•	Əməkdaşa birbaşa verilə bilən praktiki test və yoxlama sənədidir.
•	Bu sənəd əsasında bütün sistem funksionallığı addım-addım yoxlanılmalıdır.
•	Sənəd ‘buna əsasən sistemi təhvil al’ səviyyəsində hazırlanmışdır.
•	Master data-dan (istifadəçi, müştəri, məhsul, təchizatçı) başlayaraq end-to-end prosesləri (sorğu, sahə qiymətləndirməsi, tender/daxili axın, KPI, audit) əhatə edir.
•	Bütün addımlar problemsiz icra olunarsa sistem UAT mərhələsindən keçmiş hesab olunur.

UAT QƏBUL ŞƏRTİ:
Yuxarıda qeyd olunan bütün funksiyalar işlək vəziyyətdədirsə, hesabatlar və audit mexanizmləri aktivdirsə və KPI ölçümləri düzgün hesablanırsa, sistem rəsmi olaraq qəbul edilir.


UAT – TAM FUNKSİONAL QƏBUL SENARİSİ
Daxili Təchizat Zəncirinin İdarə Olunması Sistemi (SAP Ariba uyğun)
________________________________________
1️. SİSTEMƏ HAZIRLIQ (MASTER DATA YARADILMASI)
Bu mərhələ olmadan proses işləməməlidir.
1.1 İstifadəçi hesablarının yaradılması (Admin)
Admin aşağıdakı istifadəçiləri yaradır:
•	Sorğu Göndərən
•	Sahə Qiymətləndirəni
•	Təchizatçı
•	Admin (tam səlahiyyətli)
Hər istifadəçi üçün:
•	Ad, soyad
•	İstifadəçi adı
•	Rol
•	Aktiv / deaktiv status
•	Giriş hüquqları
✅ Yoxlama:
•	Roluna uyğun olmayan modullara giriş qadağandır
•	Audit log-da “İstifadəçi yaradıldı” qeydi görünür
________________________________________
1.2 Müştəri kartlarının yaradılması
Admin → Müştərilər bölməsi
Daxil edilir:
•	Müştəri adı
•	Əlaqə məlumatları
•	Status (aktiv / passiv)
✅ Yoxlama:
•	Müştəri sonradan sorğuda seçilə bilir
•	Passiv müştəri ilə sorğu açılmır
________________________________________
1.3 Məhsul kateqoriyalarının yaradılması
Admin → Məhsul Kateqoriyaları
Misal:
•	Tikinti materialları
•	Elektronika
•	Ofis ləvazimatları
✅ Yoxlama:
•	Kateqoriya sorğu zamanı seçilə bilir
________________________________________
1.4 Məhsul kartlarının yaradılması
Admin → Məhsullar
Hər məhsul üçün:
•	Məhsul adı
•	Kateqoriya
•	Texniki xüsusiyyətlər
•	Ölçü vahidi
✅ Yoxlama:
•	Məhsul yalnız öz kateqoriyasında görünür
•	Texniki tələblər sorğuya avtomatik gəlir
________________________________________
1.5 Təchizatçı kartlarının yaradılması
Admin → Təchizatçılar
Daxil edilir:
•	Təchizatçı adı
•	Əlaqə
•	Aktiv status
•	Public tender-ə açıq / qapalı
✅ Yoxlama:
•	Deaktiv təchizatçı sorğu ala bilmir
________________________________________
2️. SORĞUNUN YARADILMASI (Sorğu Göndərən)
1.	Sorğu Göndərən sistemə daxil olur
2.	“Yeni Sorğu” yaradır
3.	Aşağıdakıları seçir:
      o	Müştəri adı
      o	Məhsul kateqoriyası
      o	Məhsul adı
      o	Miqdar
      o	Çatdırılma yeri
      o	Əlavə qeydlər
4.	Sorğunu Admin-ə göndərir
      ✅ Sistem nəticəsi:
      •	Unikal Sorğu ID
      •	Status: Yeni      (Qaralama)
      •	Audit log aktivdir
________________________________________
3️. SORĞUNUN QƏBULU VƏ PARAMETRLƏR (Admin)
Admin sorğunu açır və qərar verir:
•	Sorğu tipi:
o	☐ Public (Tender – SAP Ariba uyğun)
o	☐ Daxili
•	Sahə qiymətləndirilməsi:
o	☐ Var
o	☐ Yoxdur
Sonra sorğunu Sahə Qiymətləndirənlərə yönləndirir.
✅ Yoxlama:
•	Seçilmiş parametrlər sonradan dəyişdirilə bilirmi
•	Bildirişlər gedirmi
________________________________________
4️. SAHƏ QİYMƏTLƏNDİRMƏSİ (Mobil)
1.	Sahə Qiymətləndirəni mobil tətbiqə daxil olur
2.	Sorğunu qəbul edir
3.	Daxil edir:
      o	Texniki uyğunluq
      o	Tövsiyə edilən qiymət aralığı
      o	Sahə qeydləri
4.	Admin-ə göndərir
      ✅ Yoxlama:
      •	Qəbul vaxtı və icra vaxtı qeyd olunur
      •	KPI avtomatik hesablanır
      •	Mobil və web fərqi problemsiz işləyir
________________________________________
5️. TƏCHİZATÇILARA GÖNDƏRİLMƏ (Admin)
Admin sahə qiymətləndirməsini nəzərə alaraq sorğunu:
•	Public rejimdə → minimum 3 təchizatçıya
•	Daxili rejimdə → yalnız sistem təchizatçılarına
göndərir.
✅ Yoxlama:
•	Yalnız aktiv təchizatçılar görür
•	Status: Təklif gözləyir
________________________________________
6️. QİYMƏT VERİLMƏSİ (Təchizatçı)
Təchizatçı:
•	Qiymət
•	Çatdırılma müddəti
•	Şərtlər
daxil edir və göndərir.
✅ Yoxlama:
•	Qiymət dəyişilə bilirmi
•	Son tarixdən sonra bloklanırmı
________________________________________
7️.QİYMƏTLƏRİN MÜQAYİSƏSİ (Admin)
Admin panelində:
•	Sahə qiymətləndirməsi
•	Təchizatçı qiymətləri
•	Tarix və risk
müqayisə olunur və seçim edilir.
✅ Yoxlama:
•	Seçilmiş təchizatçı işarələnir
•	Digərləri arxivlənir
________________________________________
8️.KPI, HESABATLAR VƏ BAĞLANIŞ
Sistem avtomatik hesablayır:
•	Sahə Qiymətləndirəni KPI
•	Sorğu icra müddəti
•	Təchizatçı cavab sürəti
Admin:
•	PDF / Excel hesabat çıxarır
•	Sorğunu Tamamlandı statusuna keçirir
________________________________________
9️.AUDIT VƏ QƏBUL
Yoxlanılır:
•	Kim nə vaxt nə edib
•	Status dəyişiklikləri
•	Mobil əməliyyatlar
✅ UAT Qəbul Şərti:
•	Bütün addımlar problemsiz işləyirsə
•	KPI, audit və export aktivdirsə
→ Sistem qəbul edilir
