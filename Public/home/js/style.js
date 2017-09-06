$(function(){
   /* 余额明细 */
   var incomeBox = $('.income-box');

   var income = incomeBox.children('.income-box-top'),
       incomeP = income.children('p');
   incomeP.each(function(){
       $(this).on('click',function(){
           $(this).next('.income-box-detail').fadeIn();
           $(this).parent(income).siblings().find('.income-box-detail').hide();
           $(this).children('.arrow').addClass('rotate').siblings().children('.arrow').removeClass('rotate');
           $(this).parents(income).siblings().find('.arrow').removeClass('rotate')
       })
   })
});
