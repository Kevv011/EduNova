import Toastify from 'toastify-js';

export default function useToast(message = '', options = {}) {
  const defaultValues = {
    text: message,
    duration: 3000,
    newWindow: true,
    close: true,
    gravity: 'top',
    position: 'right',
    stopOnFocus: true,
    ...options,
  };

  const error = () => {
    Toastify({
      ...defaultValues,
      style: {
        background: '#dc2626',
        borderRadius: '10px',
      },
    }).showToast();
  };

  const success = () => {
    Toastify({
      ...defaultValues,
      style: {
        background: '#22c55e',
        borderRadius: '10px',
      },
    }).showToast();
  };

  return { success, error };
}
