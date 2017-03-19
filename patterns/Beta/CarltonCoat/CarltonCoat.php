<?php
/** Freesewing\Patterns\Beta\CarltonCoat class */
namespace Freesewing\Patterns\Beta;

/**
 * A pattern template
 *
 * If you'd like to add you own pattern, you can copy this class/directory.
 * It's an empty skeleton for you to start working with
 *
 * @author Joost De Cock <joost@decock.org>
 * @copyright 2017 Joost De Cock
 * @license http://opensource.org/licenses/GPL-3.0 GNU General Public License, Version 3
 */
class CarltonCoat extends BentBodyBlock
{
    /*
        ___       _ _   _       _ _
       |_ _|_ __ (_) |_(_) __ _| (_)___  ___
        | || '_ \| | __| |/ _` | | / __|/ _ \
        | || | | | | |_| | (_| | | \__ \  __/
       |___|_| |_|_|\__|_|\__,_|_|_|___/\___|

      Things we need to do before we can draft a pattern
    */

    /** Length bonus is irrelevant */
    const LENGTH_BONUS = 0;

    /** Armhole depth factor is always 67% */
    const ARMHOLE_DEPTH_FACTOR = 0.67;

    /** Sleevecap height factor is always 50% */
    const SLEEVECAP_HEIGHT_FACTOR = 0.5;

    /** Hem from waist factor is always 69% */
    const HEM_FROM_WAIST_FACTOR = 0.69;

    /** Distance between buttons is 13% of waist */
    const BUTTON_WAIST_RATIO = 0.13;

    /** Tailfold width is 11.36% of waist */
    const TAILFOLD_WAIST_FACTOR = 0.1136;

    /**
     * Cut front armhole a bit deeper
     */
    const FRONT_ARMHOLE_EXTRA = 10;

    /**
     * Sets up options and values for our draft
     *
     * @param \Freesewing\Model $model The model to sample for
     *
     * @return void
     */
    public function initialize($model)
    {
        // The (grand)parent pattern's lengthBonus is irrelevant here
        $this->setOption('lengthBonus', self::LENGTH_BONUS);
        
        // Fix the armholeDepthFactor to 67%
        $this->setOption('armholeDepthFactor', self::ARMHOLE_DEPTH_FACTOR);
        
        // Fix the sleevecapHeightFactor to 50%
        $this->setOption('sleevecapHeightFactor', self::SLEEVECAP_HEIGHT_FACTOR);
        
        // Fix the hemFromWaistFactor to 69%
        $this->setOption('hemFromWaistFactor', self::HEM_FROM_WAIST_FACTOR);

        // Make shoulderToShoulder measurement 106.38% of original because coat
        $model->setMeasurement('shoulderToShoulder', $model->getMeasurement('shoulderToShoulder')*1.0638);
        
        // Make acrossBack measurement 106.38% of original because coat
        $model->setMeasurement('acrossBack', $model->getMeasurement('acrossBack')*1.0638);

        // Waist shaping
        $this->setValue('waistReduction', 
            ( $model->m('chestCircumference') + $this->o('chestEase') ) - 
            ( $model->m('naturalWaist') + $this->o('waistEase') ) 
        );
        // Percentage of the waistReduction that's handled in the side seams
        $this->setValue('waistSideShapeFactor', 0.5);
        $this->setValue('waistReductionSide', $this->v('waistReduction') * $this->v('waistSideShapeFactor') / 8);
        $this->setValue('waistReductionBack', $this->v('waistReduction') * (1-$this->v('waistSideShapeFactor')) / 8);

        // Distance between buttons
        $this->setValue('buttonDistHor', ($model->m('naturalWaist') * self::BUTTON_WAIST_RATIO)/2);

        // Fix the tailfoldWaistFactor to 11.36%
        $this->setValue('tailfoldWaistFactor', self::TAILFOLD_WAIST_FACTOR);

        // Width of the belt
        $this->setValue('beltWidth', 70);
        
        parent::initialize($model);
        
        // Cut front armhole a bit deeper than foreseen in parent
        $this->setValue('frontArmholeExtra', self::FRONT_ARMHOLE_EXTRA);
        
    }

    /*
        ____             __ _
       |  _ \ _ __ __ _ / _| |_
       | | | | '__/ _` | |_| __|
       | |_| | | | (_| |  _| |_
       |____/|_|  \__,_|_|  \__|

      The actual sampling/drafting of the pattern
    */

    /**
     * Generates a sample of the pattern
     *
     * Here, you create a sample of the pattern for a given model
     * and set of options. You should get a barebones pattern with only
     * what it takes to illustrate the effect of changes in
     * the sampled option or measurement.
     *
     * @param \Freesewing\Model $model The model to sample for
     *
     * @return void
     */
    public function sample($model)
    {
        // Setup all options and values we need
        $this->initialize($model);

        // Get to work
        $this->draftBackBlock($model);
        $this->draftFrontBlock($model);
        
        $this->draftSleeveBlock($model);
        $this->draftTopsleeveBlock($model);
        $this->draftUndersleeveBlock($model);
        
        $this->draftFrontCoatBlock($model);
        $this->draftBackCoatBlock($model);
        $this->draftCoatTail($model);

        // Hide the sleeveBlock, frontBlock, and backBlock
        $this->parts['sleeveBlock']->setRender(false);
        $this->parts['frontBlock']->setRender(false);
        $this->parts['backBlock']->setRender(false);
    }

    /**
     * Generates a draft of the pattern
     *
     * Here, you create the full draft of this pattern for a given model
     * and set of options. You get a complete pattern with
     * all bels and whistles.
     *
     * @param \Freesewing\Model $model The model to draft for
     *
     * @return void
     */
    public function draft($model)
    {
        // Continue from sample
        //$this->sample($model);
        $this->initialize($model);
        
        $this->draftBackBlock($model);
        //$this->finalizeBackBlock($model);
        
        $this->draftFrontBlock($model);
        //$this->finalizeFrontBlock($model);

        $this->draftSleeveBlock($model);
        $this->draftTopsleeveBlock($model);
        $this->draftUndersleeveBlock($model);
        //$this->finalizeSleeveBlock($model);
        
        $this->draftFrontCoatBlock($model);
        $this->draftBackCoatBlock($model);
        $this->draftCoatTail($model);
        
        // Hide the sleeveBlock, frontBlock, and backBlock
        $this->parts['sleeveBlock']->setRender(false);
        $this->parts['frontBlock']->setRender(false);
        $this->parts['backBlock']->setRender(false);
        
        // Is this a paperless pattern?
        if ($this->isPaperless) {
            // Add paperless info to our example part
            //$this->paperlessExamplePart($model);
        }
    }

    /**
     * Drafts the frontCoatBlock
     *
     * @param \Freesewing\Model $model The model to draft for
     *
     * @return void
     */
    public function draftFrontCoatBlock($model)
    {
        $this->clonePoints('frontBlock','frontCoatBlock');
        
        /** @var \Freesewing\Part $p */
        $p = $this->parts['frontCoatBlock'];

        // Hem length
        $p->newPoint('hemMiddle', $p->x(4), $p->y(3) + $model->m('naturalWaistToFloor') * $this->o('hemFromWaistFactor'));
        $p->newPoint('hemSide', $p->x(5), $p->y('hemMiddle'));

        // Waist shaping
        $p->newPoint('waistSide', $p->x(5) - $this->v('waistReductionSide'), $p->y(3));
        $p->addPoint('waistSideCpTop', $p->shift('waistSide', 90, $p->deltaY(5,3)/2));
        $p->addPoint('waistSideCpBottom', $p->flipY('waistSideCpTop', $p->y('waistSide')));
        $p->addPoint('chestSideCp', $p->shift(5,-90,$p->deltaY(5,'waistSideCpTop')/8));

        // Seat
        $p->newPoint('seatSide', $p->x(3) + ($model->m('seatCircumference') + $this->o('seatEase'))/4, $p->y(4) + $model->m('naturalWaistToSeat') );
        $p->addPoint('seatSideCpTop', $p->shift('seatSide', 90, $p->deltaY(4,'seatSide')/2));

        // Buttonline
        $this->setValue('buttonDistVer', $p->deltaY(4,5)/2.5);
        $p->newPoint('button1Left', $p->x(4) - $this->v('buttonDistHor'), $p->y(4));
        $p->addPoint('button2Left', $p->shift('button1Left',-90,$this->v('buttonDistVer')*1));
        $p->addPoint('button3Left', $p->shift('button1Left',-90,$this->v('buttonDistVer')*2));
        $p->addPoint('button1Right', $p->flipX('button1Left',$p->x(4)));
        $p->addPoint('button2Right', $p->flipX('button2Left',$p->x(4)));
        $p->addPoint('button3Right', $p->flipX('button3Left',$p->x(4)));

        // Front center edge
        $p->addPoint('frontEdge', $p->shift('button1Left',180,25));

        // Hem
        $p->newPoint('hemSide', $p->x('seatSide'), $p->y('hemMiddle')); 
        $p->newPoint('hemFrontEdge', $p->x('frontEdge'), $p->y('hemMiddle')); 

        // Collar
        $p->newPoint('collarEdge', $p->x('frontEdge'), $p->y(9));
        $p->addPoint('collarTip', $p->shift('collarEdge',0,$this->v('buttonDistHor')/11.5));
        $p->newPoint('collarBendPoint', $p->x('collarEdge'), $p->y(5));
        $p->addPoint('collarBendPointCpTop', $p->shift('collarBendPoint',90,$p->deltaY('collarEdge','collarBendPoint')*0.8));

        // FIXME: Move to finalize, but buttons are visual aids for now
        $p->newSnippet('button1Left','button','button1Left');
        $p->newSnippet('button2Left','button','button2Left');
        $p->newSnippet('button3Left','button','button3Left');
        $p->newSnippet('button1Right','button','button1Right');
        $p->newSnippet('button2Right','button','button2Right');
        $p->newSnippet('button3Right','button','button3Right');

        // Paths 
        $path = 'M 9 L collarTip 
            C collarTip collarBendPointCpTop collarBendPoint
            L hemFrontEdge L hemSide L seatSide 
            C seatSideCpTop waistSideCpBottom waistSide 
            C waistSideCpTop chestSideCp 5 
            C 13 16 14 C 15 18 10 C 17 19 12 L 8 C 20 21 9 z';
        $p->newPath('seamline', $path);
        $p->newPath('hipLine', 'M 4 L 6 L frontEdge', ['class' => 'helpline']);
    }

    /**
     * Drafts the backCoatBlock
     *
     * @param \Freesewing\Model $model The model to draft for
     *
     * @return void
     */
    public function draftBackCoatBlock($model)
    {
        $this->clonePoints('backBlock','backCoatBlock');
        
        /** @var \Freesewing\Part $p */
        $p = $this->parts['backCoatBlock'];

        // Box pleat
        $p->newPoint('bpTop', $p->x(1) - $model->m('chestCircumference') * 0.048, $p->y(10));
        $p->newPoint('bpTopIn', $p->x(1), $p->y(10));
        $p->newPoint('bpBottom', $p->x('bpTop'), $p->y(3) - $this->v('beltWidth')/2);
         
        // Waist shaping
        $p->newPoint('waistSide', $p->x(5) - $this->v('waistReductionSide'), $p->y(3) - $this->v('beltWidth')/2);
        $p->addPoint('waistSideCpTop', $p->shift('waistSide', 90, ($p->deltaY(5,3)/2) - ($this->v('beltWidth')/2)));
        $p->addPoint('chestSideCp', $p->shift(5,-90,$p->deltaY(5,'waistSideCpTop')/8));

        // Darts
        $p->newPoint('dartCenter', $p->x('waistSide') * 0.4, $p->y('waistSide'));
        $p->addPoint('dartRight', $p->shift('dartCenter', 0, $this->v('waistReductionBack')));
        $p->addPoint('dartLeft', $p->shift('dartCenter', 180, $this->v('waistReductionBack')));
        $p->newPoint('dartTip', $p->x('dartCenter'), $p->y(5));
        $p->addPoint('dartRightCp', $p->shift('dartRight', 90, $p->deltaY(5,'dartCenter')/2));
        $p->addPoint('dartLeftCp', $p->shift('dartLeft', 90, $p->deltaY(5,'dartCenter')/2));
        // Paths
        $path = 'M 1 L bpTopIn L bpTop L bpBottom L dartLeft C dartLeftCp dartTip dartTip C dartTip dartRightCp dartRight L waistSide C waistSideCpTop chestSideCp 5 C 13 16 14 C 15 18 10 C 17 19 12 L 8 C 20 1 1 z';
        $p->newPath('seamline', $path, ['class' => 'fabric']);

        $p->newPath('boxPleat', 'M bpTopIn L bpTop L bpBottom'); 
    }

    /**
     * Drafts the coatTail
     *
     * @param \Freesewing\Model $model The model to draft for
     *
     * @return void
     */
    public function draftCoatTail($model)
    {
        /** @var \Freesewing\Part $p */
        $b = $this->parts['backCoatBlock'];
        $waist = $b->x('waistSide') - $this->v('waistReductionBack')*2;

        /** @var \Freesewing\Part $p */
        $f = $this->parts['frontCoatBlock'];
        $length = $f->y('hemSide') - $f->y('waistSide') - $this->v('beltWidth');

        /** @var \Freesewing\Part $p */
        $p = $this->parts['coatTail'];

        $p->newPoint('cbTop', 0, 0);
        $p->newPoint('waistTop', $waist, 0);
        $p->newPoint('leftTop', $model->m('naturalWaist') * $this->v('tailfoldWaistFactor') * -2, 0);
        $p->newPoint('leftPleat1', $model->m('naturalWaist') * $this->v('tailfoldWaistFactor') * -1.5, 0);
        $p->newPoint('leftPleat2', $model->m('naturalWaist') * $this->v('tailfoldWaistFactor') * -1.0, 0);
        $p->newPoint('leftPleat3', $model->m('naturalWaist') * $this->v('tailfoldWaistFactor') * 0.5, 0);

        foreach($p->points as $id => $point) {
            $p->addPoint("$id-1", $p->shift($id,-90,50));
            $p->addPoint("$id-2", $p->shift($id,-90,80));
            $p->addPoint("$id-3", $p->shift($id,-90,130));
        }

        $p->addPoint('dimTop', $p->shift('waistTop', 180, 70));
        $p->addPoint('dimBottom', $p->shift('waistTop-3', 180, 70));

        $p->newLinearDimension('dimBottom', 'dimTop', 0, $p->unit($length));

        // Paths
        $p->newPath('seamline1', 'M leftTop-1 leftTop L cbTop L waistTop L waistTop-1', ['class' => 'fabric']);
        $p->newPath('seamline2', 'M leftTop-2 leftTop-3 L cbTop-3 L waistTop-3 L waistTop-2', ['class' => 'fabric']);
        $p->newPath('folds', '
            M leftPleat1 L leftPleat1-3
            M leftPleat2 L leftPleat2-3
            M cbTop L cbTop-3
            M leftPleat3 L leftPleat3-3
        ', ['class' => 'dashed']);
        $p->newPath('dots', 'M leftTop-1 L leftTop-2 M waistTop-1 L waistTop-2', ['class' => 'help sa']);
    }

    /*
       _____ _             _ _
      |  ___(_)_ __   __ _| (_)_______
      | |_  | | '_ \ / _` | | |_  / _ \
      |  _| | | | | | (_| | | |/ /  __/
      |_|   |_|_| |_|\__,_|_|_/___\___|

      Adding titles/logos/seam-allowance/grainline and so on
    */

    /**
     * Finalizes the example part
     *
     * @param \Freesewing\Model $model The model to draft for
     *
     * @return void
     */
    public function finalizeExamplePart($model)
    {
        /** @var \Freesewing\Part $p */
        $p = $this->parts['examplePart'];
    }

    /*
        ____                       _
       |  _ \ __ _ _ __   ___ _ __| | ___  ___ ___
       | |_) / _` | '_ \ / _ \ '__| |/ _ \/ __/ __|
       |  __/ (_| | |_) |  __/ |  | |  __/\__ \__ \
       |_|   \__,_| .__/ \___|_|  |_|\___||___/___/
                  |_|

      Instructions for paperless patterns
    */

    /**
     * Adds paperless info for the example part
     *
     * @param \Freesewing\Model $model The model to draft for
     *
     * @return void
     */
    public function paperlessExamplePart($model)
    {
        /** @var \Freesewing\Part $p */
        $p = $this->parts['examplePart'];
    }
}