(function(){
  function ready(fn){
    if(document.readyState !== 'loading'){ fn(); }
    else { document.addEventListener('DOMContentLoaded', fn); }
  }

  ready(function(){
    var paidUrl = (window && window.TAGIHAN_PAID_URL) ? window.TAGIHAN_PAID_URL : null;
    var unpaidUrl = (window && window.TAGIHAN_UNPAID_URL) ? window.TAGIHAN_UNPAID_URL : null;

    var toPaidTab = document.querySelector('[data-tab="sudah"]');
    if (toPaidTab && paidUrl){
      toPaidTab.addEventListener('click', function(e){
        e.preventDefault();
        window.location.href = paidUrl;
      });
    }

    var markPaidButtons = document.querySelectorAll('[data-action="mark-paid"]');
    if (markPaidButtons && paidUrl){
      markPaidButtons.forEach(function(btn){
        btn.addEventListener('click', function(e){
          e.preventDefault();
          window.location.href = paidUrl;
        });
      });
    }

    // Navigate back to Belum Dibayar tab if available
    var toUnpaidTab = document.querySelector('[data-tab="belum"]');
    if (toUnpaidTab && unpaidUrl){
      toUnpaidTab.addEventListener('click', function(e){
        e.preventDefault();
        window.location.href = unpaidUrl;
      });
    }
  });
})();