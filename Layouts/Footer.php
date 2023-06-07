<?php
?>
<footer class="bg-dark d-flex w-100 justify-content-between" id="footer">
  
    <ul class="d-flex align-items-center" style="
      list-style: none;
      margin: 0;
      padding: 0;">
        <?php if (isset($this->d['user'])) : ?>
          <?php  if ($this->d['user']->getRole() == "admin") : ?>
            <li><a class="btn h-100 btn-danger" href="phpmyadmin/" target="_blank">DATABASE</a></li>
            <li><p class="text-light m-0">|</p></li>
          <?php endif;?>
        <?php endif;?>
        <li><a class="btn h-100 btn-dark" data-bs-toggle="modal" data-bs-target="#terminosModal">Política de privacidad</a></li>
        <li><p class="text-light m-0">|</p></li>
        <li><a class="btn h-100 btn-dark" data-bs-toggle="modal" data-bs-target="#cookiesModal">Política de cookies</a></li>
        <li><p class="text-light m-0">|</p></li>
        <li><a class="btn h-100 btn-dark" href="https://aventurama.es/contacta-con-nosotros/">Contactanos</a></li>
    </ul>
    <div class="d-flex px-2">
      <ul class="d-flex align-items-center gap-3" style="
        list-style: none;
        margin: 0;
        padding: 0;">
            <li><a target="_blank" class="btn btn-sm btn-primary" href="https://www.facebook.com/AVENTURAMA/"><img src="https://aventurama.es/wp-content/uploads/fbtop1.png" alt="Facebook" width="16" height="16" class="alignnone size-full wp-image-2950"></a></li>
            <!--<li><a href="#"><img src="https://aventurama.es/wp-content/uploads/twtop.png" alt="Twitter" width="16" height="16" class="alignnone size-full wp-image-2951" /></a></li><li><a href="#"><img src="https://aventurama.es/wp-content/uploads/lktop.png" alt="Linkedin" width="16" height="16" class="alignnone size-full wp-image-2952" /></a></li><li><a href="#"><img src="https://aventurama.es/wp-content/uploads/ytbtop.png" alt="Youtube" width="16" height="16" class="alignnone size-full wp-image-2953" /></a></li>-->
            <li><a class="btn btn-sm btn-primary" href="https://aventurama.es/novedades/"><img src="https://aventurama.es/wp-content/uploads/rsstop.png" alt="RSS" width="16" height="16" class="alignnone size-full wp-image-2954"></a></li>
        </ul>
  </div>
</footer>

<div class="modal fade" id="terminosModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Política de Privacidad</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
                <div class="modal-body">
                <article class="cuerpo intro tipcuerpo textleft">
                <p style="text-align: justify;">El responsable del tratamiento de los datos de identificación personal recogidos a través de la web a la que se accede desde el dominio<a href="https://aventurama.es/"> www.aventurama.es</a> es Aventurama, SL con domicilio en Tiberiades 8, 5ºA. 28043. Madrid</p>
                <p style="text-align: justify;">Todos los datos facilitados por los usuarios como consecuencia de la utilización y/o solicitud de los servicios ofrecidos por Aventurama, SL a través de la Web, serán objeto de tratamiento automatizado e incorporados a los correspondientes ficheros cuyo responsable es la empresa Aventurama, SL. Los usuarios que faciliten datos de carácter personal, consienten inequívocamente la incorporación de los datos facilitados a dichos ficheros automatizados y al tratamiento informatizado o no de los mismos con fines de publicidad y de prospección comercial. Cuando la utilización o solicitud de algún servicio requiera la facilitación de datos de carácter personal, los usuarios garantizarán su exactitud, autenticidad y vigencia. En base a lo expuesto, los usuarios tendrán la obligación de mantener actualizados los datos, de tal forma que correspondan a la realidad en cada momento.</p>
                <p style="text-align: justify;">Los usuarios cuyos datos sean objeto de tratamiento podrán ejercitar gratuitamente los derechos de oposición, acceso e información, rectificación, cancelación de sus datos y revocación de su autorización, sin efectos retroactivos en los términos especificados en la Ley Orgánica 15/1999 de Protección de Datos de Carácter Personal y Reglamento que lo desarrolla, conforme al procedimiento legalmente establecido. Estos derechos podrán ser ejercitados mediante el envío de un correo electrónico a <a href="mailto:informacion@aventurama.es">informacion@aventurama.es</a> o por comunicación escrita dirigida a Aventurama, SL con domicilio en Tiberiades 8, 5ºA. 28043. Madrid Los datos incorporados a los ficheros automatizados de Aventurama, SL. Únicamente serán utilizados con la finalidad de posibilitar el normal desarrollo de los servicios e informaciones solicitados por los usuarios, efectuar estadísticas y demás finalidades derivadas de las actividades y servicios propios de Aventurama, SL.</p>
                <p style="text-align: justify;">Al facilitarnos su dirección de correo electrónico o el teléfono nos estará autorizando expresamente a que le remitamos comunicaciones publicitarias o promocionales por el medio de contacto facilitado, conforme a lo establecido en el artículo 21 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información. Para la cancelación de las comunicaciones electrónicas, por favor diríjase a Aventurama, SL. Aventurama, SL se compromete a no recabar información innecesaria sobre sus usuarios, indicándoles en el momento de proceder a la recogida de los datos el carácter obligatorio o facultativo de las respuestas a las cuestiones que le sean planteadas; a tratar con extrema diligencia la información personal que le puedan facilitar los usuarios; y a cumplir en cualquier fase del tratamiento con la obligación de guardar secreto respecto de los datos facilitados por éstos. Aventurama, SL ha adoptado las medidas técnicas y organizativas necesarias para garantizar la seguridad e integridad de los datos, así como para evitar su alteración, pérdida, tratamiento o acceso no autorizado conforme a la legislación vigente en materia de protección de datos. Aventurama, SL se reserva el derecho a modificar la política de privacidad, siempre en los términos permitidos por la legislación española vigente y previa comunicación a los interesados, bien mediante publicación en esta página o por cualquier otro medio de comunicación o difusión que se considere oportuno.</p>
                </article>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="cookiesModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Política de Cookies</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
                <div class="modal-body">
                <article class="cuerpo intro tipcuerpo textleft">
<p style="text-align: justify;">En nuestra web dedicada a&nbsp;<a href="https://aventurama.es/campamentos-de-verano/">campamentos de verano</a>&nbsp;, <a href="https://aventurama.es/animaciones/">animaciones de fiestas infantiles</a> y <a href="https://aventurama.es/actividades-extraescolares/">actividades extraescolares en Madrid</a> utilizamos cookies de analítica web para medir y analizar la navegación de los usuarios en nuestra página web. Las cookies son pequeños archivos que se pueden instalar en su equipo a través de las páginas web y que se alojan en su navegador durante un periodo de tiempo determinado previamente.</p>
<p style="text-align: justify;">Las cookies de analítica son aquéllas que tienen la finalidad de cuantificar el número de visitantes y analizar estadísticamente la utilización que hacen los usuarios de nuestros servicios. Gracias a ello podemos estudiar la navegación por nuestra página web, y mejorar así la oferta de productos o servicios que le ofrecemos.</p>
<p style="text-align: justify;">A través de la analítica web no obtenemos información de carácter personal como su nombre, apellidos, su dirección de correo electrónico o postal. La información que obtenemos es la relativa al número de usuarios que acceden a la web, el número de páginas vistas, la frecuencia y repetición de las visitas, su duración, el navegador que utiliza, el operador que le presta el servicio de acceso a Internet, su idioma, el dispositivo usado para visualizar nuestra web o la ciudad a la que está asignada su dirección IP.</p>
<p style="text-align: justify;">En nuestra web de <a href="https://aventurama.es/">Aventurama</a> utilizamos Google Analytics, un servicio de analítica web desarrollada por Google, que presta un servicio de medición y análisis de la navegación en nuestra página web. Google puede utilizar los datos para mejorar sus servicios y ofrecer servicios a otras empresas. Puede encontrar más información al respecto e inhabilitar el uso de estas cookies aquí</p>
<h3 style="font-size: 20px; text-align: justify;">¿Cómo puedo deshabilitar las cookies en los principales navegadores?</h3>
<p style="text-align: justify;"><strong>Internet Explorer</strong>: Herramientas -&gt; Opciones de Internet -&gt; Privacidad -&gt; Configuración. Para más información, puede consultar el soporte de <strong><a href="http://windows.microsoft.com/es-ES/windows/support#1TC=windows-7&amp;top-solutions=windows-7" target="_blank" rel="nofollow noopener noreferrer">Microsoft</a></strong> o la Ayuda del navegador.</p>
<p style="text-align: justify;"><strong>Firefox</strong>: Herramientas -&gt; Opciones -&gt; Privacidad -&gt; Historial -&gt; Configuración Personalizada. Para más información, puede consultar el soporte de <strong><a href="https://support.mozilla.org/es/" target="_blank" rel="nofollow noopener noreferrer">Mozilla</a></strong> o la Ayuda del navegador.</p>
<p style="text-align: justify;"><strong>Chrome</strong>: Configuración -&gt; Mostrar opciones avanzadas -&gt; Privacidad -&gt; Configuración de contenido. Para más información, puede consultar el soporte de <strong><a href="https://support.google.com/chrome/?hl=es#topic=3227046" target="_blank" rel="nofollow noopener noreferrer">Google</a></strong> o la Ayuda del navegador.</p>
<p style="text-align: justify;"><strong>Safari</strong>: Preferencias -&gt; Seguridad. Para más información, puede consultar el soporte de <strong><a href="http://www.apple.com/es/support/mac-apps/safari/" target="_blank" rel="nofollow noopener noreferrer">Apple</a></strong> o la Ayuda del navegador.</p>
</article>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            
        </div>
    </div>
</div>