
<div class="report">
<!--    <p style="display: block; height: 1cm;">&nbsp;</p>-->
    <h4 class="text-center" style="font-size: 18px; ">
        Sale Report<?php echo isset($report_no) &&$report_no!=""?"($report_no)":"";?>
<!--        Working Place : --><?php //echo isset($employer_name)? $employer_name:"";?><!--<br/>-->
<!--        Address : --><?php //echo isset($employer_address) ? $employer_address:"";?><!--<br/>-->
<!--        Sending Company : --><?php //echo isset($company_name)? $company_name:"";?>
    </h4>

    <table id="register-table" class="table table-striped table-bordered report">
        <thead>
        <tr>
            <th class="text-center"> No. </th>
            <th > Sale Date </th>
            <th > Sale No </th>
            <th > Card Name </th>
            <th class="text-right"> Amount</th>
            <th class="text-right"> Discount</th>
            <th class="text-right"> Total</th>
        </tr>

        </thead>
        <tbody>
        <?php if(isset($sales) && is_array($sales)){
            $no = 0;
            foreach($sales as $row){
                $no++;
                $date = strtotime($row->journal_date);
                $sale_date = Date("d-m-Y",$date);
                ?>
                <tr>
                    <td class="text-center"><?php echo $no;?></td>
                    <td class="text-center"><?php echo $sale_date?></td>
                    <td class="text-center"><?php echo $row->journal_no;?></td>
                    <td ><?php echo isset($row->card_name)? $row->card_name:"";?></td>
                    <td class="text-right">$ <?php echo isset($row->total)? $row->total:"";?></td>
                    <td class="text-right"><?php echo isset($row->discount)? $row->discount:"0";?> %</td>
                    <td class="text-right">$ <?php echo $row->get_total_after_discount();?></td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
    </table>

    <?php if(!isset($sales) || !is_array($sales) || count($sales) == 0 ){ ?>
        <h4 class="red">No records found</h4>
    <?php }?>

    <br>
<!--    <table width="100%">-->
<!--        <tr>-->
<!--            <td> </td>-->
<!--            <td width="360px" align="center" >​ Phnom Penh <span style="display: inline-block; width: 110px;"></span> --><?php //echo Date("Y");?><!-- </td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td></td>-->
<!--            <td style="width: 32px; text-align: center; font-family: 'Times New Roman'; font-size: 12px; padding-top: 10px;" >GENERAL DEPARTMENT OF LABOUR</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td></td>-->
<!--            <td width="360px" align="center" >-->
<!--                <br/><br/><br/><br/>-->
<!--                Dr. SENG SAKDA<br/>-->
<!--                DIRECTOR GENERAL-->
<!--            </td>-->
<!--        </tr>-->
<!--    </table>-->


</div>
