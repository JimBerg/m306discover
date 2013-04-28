<?php
/**
 * History Template
 * Layout for users game history
 *
 * @author: Janina Imberg
 * @version: 1.0
 * @date: 27.04.2013
 *
 */
?>

<?php if( isset( $history->noQuests ) ): ?>
    NOCH KEINE AUFGABE
<?php else: ?>
    <?php foreach( $history as $item ): ?>
        <div class="container history <?php echo Helper::historyCssClass( $item->solved ); ?>">
            <div class="arrow"></div>
            <div class="history-wrapper">
                <div class="history-head">
                    <div class="icon"></div>
                    <h1><?php echo Helper::formatQuest( $item->location_id ); ?></h1>
                    <h2 class="subtitle"><?php echo Helper::formatSolved( $item->solved ); ?></h2>
                    <h2 class="date"><?php echo Helper::formatDate( $item->visitdate ); ?></h2>
                </div>
                <div class="history-content">
                    <ul class="history-summary">
                        <li>
                            <h3>Punkte</h3>
                            <div><?php echo $item->points; ?></div>
                        </li>
                        <li class="middle">
                            <h3>Versuche</h3>
                            <div><?php echo $item->counter; ?></div>
                        </li>
                        <li>
                            <h3>Gesamt</h3>
                            <div>80</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


