// JavaScript untuk mengelola tampilan pop-up
document.addEventListener("DOMContentLoaded", () => {
    const openPopupButton = document.getElementById('openPopup');
    const closePopupButton = document.getElementById('closePopup');
    const popup = document.getElementById('popup');
  
    // Menampilkan pop-up ketika tombol "Tambah Survei" diklik
    openPopupButton.addEventListener('click', () => {
      popup.classList.remove('hidden');
    });
  
    // Menyembunyikan pop-up ketika tombol "Batal" atau tombol close (X) diklik
    closePopupButton.addEventListener('click', () => {
      popup.classList.add('hidden');
    });
  
    // Menyembunyikan pop-up jika klik di luar area pop-up
    window.addEventListener('click', (event) => {
      if (event.target === popup) {
        popup.classList.add('hidden');
      }
    });
});
