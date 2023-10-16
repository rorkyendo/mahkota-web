    <ul class="chats">
        <?php foreach($getIsi as $row):?>
        <?php if(empty($row->created_by)):?>
        <li class="left">
            <span class="date-time"><?php echo $row->waktu_pesan;?></span>
            <a href="javascript:;" class="name"><?php echo $row->nama_lengkap;?></a>
            <?php if(!empty($row->foto_pengguna)):?>
            <a href="javascript:;" class="image"><img alt="" src="<?php echo base_url().$row->foto_pengguna;?>" /></a>
            <?php else:?>
            <a href="javascript:;" class="image"><img alt="" src="<?php echo base_url().$appsProfile->logo;?>" /></a>
            <?php endif;?>
            <div class="message">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <br />
                <?php if(!empty($row->lampiran)):?>
                <img src="<?php echo base_url().$row->lampiran;?>" class="img-responsive"
                    style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </div>
        </li>
        <?php else:?>
        <?php if($row->created_by == $pengguna):?>
        <li class="left">
            <span class="date-time"><?php echo $row->waktu_pesan;?></span>
            <a href="javascript:;" class="name"><?php echo $row->nama_lengkap;?></a>
            <?php if(!empty($row->foto_pengguna)):?>
            <a href="javascript:;" class="image"><img alt="" src="<?php echo base_url().$row->foto_pengguna;?>" /></a>
            <?php else:?>
            <a href="javascript:;" class="image"><img alt="" src="<?php echo base_url().$appsProfile->logo;?>" /></a>
            <?php endif;?>
            <div class="message">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <br />
                <?php if(!empty($row->lampiran)):?>
                <img src="<?php echo base_url().$row->lampiran;?>" class="img-responsive"
                    style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </div>
        </li>
        <?php else:?>
        <li class="right">
            <span class="date-time"><?php echo $row->waktu_pesan;?>&nbsp;&nbsp;<a href="#"
                    onclick="batal('<?php echo my_simple_crypt($row->id_isi_tiket,'e');?>')"><span
                        class="label label-danger">batalkan</span></span>
            <a href="#" class="name"><span class="label label-primary">ADMIN</span>
                <?php echo $row->nama_lengkap;?></a>
            <?php if(!empty($row->foto_pengguna)):?>
            <a href="javascript:;" class="image"><img alt="" src="<?php echo base_url().$row->foto_pengguna;?>" /></a>
            <?php else:?>
            <a href="javascript:;" class="image"><img alt="" src="<?php echo base_url().$appsProfile->logo;?>" /></a>
            <?php endif;?>
            <div class="message">
                <?php if($row->status!='cancel'):?>
                <?php echo $row->pesan;?>
                <br />
                <?php if(!empty($row->lampiran)):?>
                <img src="<?php echo base_url().$row->lampiran;?>" class="img-responsive"
                    style="width:320px;height:280px;">
                <?php endif;?>
                <?php else:?>
                <i>Pesan telah ditarik</i>
                <?php endif;?>
            </div>
        </li>
        <?php endif;?>
        <?php endif;?>
        <?php endforeach;?>
    </ul>