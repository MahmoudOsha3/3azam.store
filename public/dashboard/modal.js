
    const openModalBtn = document.getElementById('openModalBtn');
    const openModalBtnEdit = document.getElementById('openModalBtnEdit');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeModalBtnEdit = document.getElementById('closeModalBtnEdit');
    const cancelFormBtnEdit = document.getElementById('cancelFormBtnEdit');
    const myModal = document.getElementById('myModal');
    const ModalEdit = document.getElementById('ModalEdit');

    openModalBtn.addEventListener('click', () => {
        myModal.classList.remove('hidden');
        myModal.classList.add('flex'); // لجعل flexbox يعمل لعرض الـ Modal
    });

    openModalBtnEdit.addEventListener('click', () => {
        ModalEdit.classList.remove('hidden');
        ModalEdit.classList.add('flex'); // لجعل flexbox يعمل لعرض الـ Modal
    });

    const hideModal = () => {
        myModal.classList.add('hidden');
        myModal.classList.remove('flex');
    };

    const hideModalEdit = () => {
        ModalEdit.classList.add('hidden');
        ModalEdit.classList.remove('flex');
    };

    closeModalBtn.addEventListener('click', hideModal);
    cancelFormBtn.addEventListener('click', hideModal);

    closeModalBtnEdit.addEventListener('click', hideModalEdit);
    cancelFormBtnEdit.addEventListener('click', hideModalEdit);
    // إغلاق الـ Modal عند النقر خارج المحتوى
    ModalEdit.addEventListener('click', (e) => {
        if (e.target === ModalEdit) {
            hideModalEdit();
        }
    });

        myModal.addEventListener('click', (e) => {
        if (e.target === myModal) {
            hideModal();
        }
    });

    // إغلاق الـ Modal عند الضغط على زر Esc
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !myModal.classList.contains('hidden')) {
            hideModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !ModalEdit.classList.contains('hidden')) {
            hideModalEdit();
        }
    });
